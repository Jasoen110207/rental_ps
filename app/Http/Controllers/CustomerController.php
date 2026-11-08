<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tv;
use App\Models\User;
use App\Notifications\CustomerRequestNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Pintu gerbang saat pelanggan men-scan QR Code fisik di meja (/scan/{tv_token}).
     */
    public function scan(Request $request, string $token)
    {
        $storeName = Setting::get('store_name', 'TambahBang Rental PS');
        $tv = Tv::where('access_token', $token)->first();

        if (! $tv) {
            return response()
                ->view('customer.expired', [
                    'storeName' => $storeName,
                    'title' => 'QR CODE TIDAK VALID',
                    'message' => 'Kode QR meja tidak dikenali. Silakan hubungi petugas kasir.',
                ], 404)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        }

        $activeSession = PlaySession::where('tv_id', $tv->id)
            ->where('status', 'active')
            ->first();

        if (! $activeSession) {
            return response()
                ->view('customer.expired', [
                    'tv' => $tv,
                    'storeName' => $storeName,
                    'title' => 'MEJA BELUM AKTIF',
                    'message' => 'Unit '.$tv->name.' saat ini belum memiliki sesi bermain aktif. Silakan mulai rental di kasir terlebih dahulu lalu scan kembali QR Code ini.',
                ], 403)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        }

        if (empty($activeSession->session_token)) {
            $activeSession->update(['session_token' => Str::random(40)]);
        }

        // Tandai bahwa browser HP ini masuk melalui scan QR fisik yang sah untuk sesi ini
        $request->session()->put('qr_verified_'.$activeSession->session_token, true);

        return redirect()
            ->route('customer.index', ['token' => $activeSession->session_token])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Validasi session_token dan pastikan sesi masih aktif serta telah melalui scan QR.
     */
    protected function resolveValidActiveSession(Request $request, string $token): ?PlaySession
    {
        $session = PlaySession::with(['tv', 'sessionOrders.product'])
            ->where('session_token', $token)
            ->first();

        if (! $session || $session->status !== 'active') {
            return null;
        }

        // Pastikan waktu prepaid belum habis
        if ($session->billing_type === 'prepaid' && $session->end_time && $session->end_time->isPast()) {
            return null;
        }

        // Pastikan browser memiliki penanda sesi hasil scan QR (/scan/{tv_token})
        if (! $request->session()->get('qr_verified_'.$session->session_token, false)) {
            return null;
        }

        return $session;
    }

    public function index(Request $request, string $token)
    {
        $storeName = Setting::get('store_name', 'TambahBang Rental PS');

        $activeSession = $this->resolveValidActiveSession($request, $token);

        if (! $activeSession) {
            $pastSession = PlaySession::with('tv')->where('session_token', $token)->first();

            return response()
                ->view('customer.expired', [
                    'tv' => $pastSession?->tv,
                    'storeName' => $storeName,
                    'title' => 'SESI RENTAL TELAH BERAKHIR',
                    'message' => 'Halaman ini sudah tidak berlaku karena sesi bermain telah selesai atau dibuka melalui riwayat browser. Silakan scan ulang QR Code di meja TV.',
                ], 403)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        }

        $tv = $activeSession->tv;

        $products = Product::where('is_available', true)
            ->where('stock', '>', 0)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $requests = CustomerRequest::where('tv_id', $tv->id)
            ->where('created_at', '>=', $activeSession->start_time)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()
            ->view('customer.index', compact('tv', 'activeSession', 'products', 'requests', 'storeName'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function requestAddTime(Request $request, string $token)
    {
        $activeSession = $this->resolveValidActiveSession($request, $token);
        if (! $activeSession) {
            return redirect()->route('customer.index', ['token' => $token])
                ->with('error', 'Sesi bermain sudah berakhir atau tidak valid. Silakan scan ulang QR Code di meja.');
        }

        $tv = $activeSession->tv;

        $validated = $request->validate([
            'duration_hours' => 'required|numeric|min:0.01|max:12',
            'note' => 'nullable|string|max:255',
        ]);

        if ($activeSession->billing_type === 'postpaid') {
            return back()->with('error', 'Tidak dapat menambah waktu untuk sesi Open Billing (Postpaid).');
        }

        $hours = (float) $validated['duration_hours'];
        $estimatedPrice = (int) round($hours * $tv->price_per_hour);

        $customerRequest = CustomerRequest::create([
            'tv_id' => $tv->id,
            'type' => 'add_time',
            'payload' => [
                'duration_hours' => $hours,
                'price' => $estimatedPrice,
                'note' => $validated['note'] ?? ('Tambah '.($hours == 1 ? '1 Jam' : ($hours.' Jam'))),
            ],
            'status' => 'pending',
        ]);

        $kasirAndAdmin = User::whereIn('role', ['kasir', 'admin'])->get();
        Notification::send($kasirAndAdmin, new CustomerRequestNotification($customerRequest));

        return back()->with('success', 'Permintaan tambah waktu berhasil dikirim ke kasir! Mohon tunggu konfirmasi.');
    }

    public function requestFood(Request $request, string $token)
    {
        $activeSession = $this->resolveValidActiveSession($request, $token);
        if (! $activeSession) {
            return redirect()->route('customer.index', ['token' => $token])
                ->with('error', 'Sesi bermain sudah berakhir atau tidak valid. Silakan scan ulang QR Code di meja.');
        }

        $tv = $activeSession->tv;

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:0|max:20',
            'note' => 'nullable|string|max:255',
        ]);

        $orderItems = [];
        $totalPrice = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $item['quantity'] > 0) {
                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => (int) $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        if (empty($orderItems)) {
            return back()->with('error', 'Pilih minimal satu menu!');
        }

        foreach ($orderItems as $oi) {
            $product = Product::find($oi['product_id']);
            if (! $product || $product->stock < $oi['quantity']) {
                return back()->with('error', 'Stok "'.($product->name ?? 'menu').'" tidak mencukupi (sisa '.($product->stock ?? 0).').');
            }
        }

        $customerRequest = CustomerRequest::create([
            'tv_id' => $tv->id,
            'type' => 'order_food',
            'payload' => [
                'items' => $orderItems,
                'total_price' => $totalPrice,
                'note' => $validated['note'] ?? null,
            ],
            'status' => 'pending',
        ]);

        $kasirAndAdmin = User::whereIn('role', ['kasir', 'admin'])->get();
        Notification::send($kasirAndAdmin, new CustomerRequestNotification($customerRequest));

        return back()->with('success', 'Pesanan makanan & minuman berhasil dikirim ke kasir!');
    }

    /**
     * Halaman order cepat (satu menu sekali klik) — dinamis per sesi meja.
     */
    public function order(Request $request, string $token)
    {
        $storeName = Setting::get('store_name', 'TambahBang Rental PS');
        $activeSession = $this->resolveValidActiveSession($request, $token);

        if (! $activeSession) {
            return response()
                ->view('customer.expired', [
                    'storeName' => $storeName,
                    'title' => 'SESI RENTAL TELAH BERAKHIR',
                    'message' => 'Halaman ini sudah tidak berlaku karena sesi bermain telah selesai atau dibuka melalui riwayat browser. Silakan scan ulang QR Code di meja TV.',
                ], 403)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        }

        $tv = $activeSession->tv;

        $products = Product::where('is_available', true)
            ->where('stock', '>', 0)
            ->orderBy('category')
            ->orderBy('name')
            ->take(8)
            ->get();

        $requests = CustomerRequest::where('tv_id', $tv->id)
            ->where('created_at', '>=', $activeSession->start_time)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()
            ->view('customer.order', compact('tv', 'activeSession', 'products', 'requests', 'storeName'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Panggil kasir ke meja (service call). Disetujui kasir tanpa mengubah tagihan.
     */
    public function callCashier(Request $request, string $token)
    {
        $activeSession = $this->resolveValidActiveSession($request, $token);
        if (! $activeSession) {
            return redirect()->route('customer.index', ['token' => $token])
                ->with('error', 'Sesi bermain sudah berakhir atau tidak valid. Silakan scan ulang QR Code di meja.');
        }

        $tv = $activeSession->tv;

        $validated = $request->validate([
            'note' => 'nullable|string|max:255',
        ]);

        // Hindari spam: tolak jika masih ada service call pending < 2 menit terakhir
        $recent = CustomerRequest::where('tv_id', $tv->id)
            ->where('type', 'service_call')
            ->where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subMinutes(2))
            ->exists();
        if ($recent) {
            return back()->with('error', 'Kasir sudah dipanggil, mohon tunggu sebentar!');
        }

        $customerRequest = CustomerRequest::create([
            'tv_id' => $tv->id,
            'type' => 'service_call',
            'payload' => [
                'note' => $validated['note'] ?? 'Pelanggan memanggil kasir ke meja.',
            ],
            'status' => 'pending',
        ]);

        $kasirAndAdmin = User::whereIn('role', ['kasir', 'admin'])->get();
        Notification::send($kasirAndAdmin, new CustomerRequestNotification($customerRequest));

        return back()->with('success', 'Kasir sedang menuju ke meja '.$tv->name.'!');
    }

    public function apiStatus(Request $request, string $token)
    {
        $activeSession = $this->resolveValidActiveSession($request, $token);

        if (! $activeSession) {
            return response()->json([
                'expired' => true,
                'session' => null,
                'requests' => [],
            ]);
        }

        $tv = $activeSession->tv;
        $now = Carbon::now();

        $elapsedSeconds = $activeSession->start_time->diffInSeconds($now);
        $remainingSeconds = 0;
        $isTimeUp = false;

        if ($activeSession->billing_type === 'prepaid' && $activeSession->end_time) {
            if ($activeSession->end_time->isPast()) {
                $remainingSeconds = 0;
                $isTimeUp = true;
            } else {
                $remainingSeconds = $now->diffInSeconds($activeSession->end_time, false);
            }
        }

        $sessionInfo = [
            'id' => $activeSession->id,
            'billing_type' => $activeSession->billing_type,
            'start_time' => $activeSession->start_time->format('H:i'),
            'end_time' => $activeSession->end_time ? $activeSession->end_time->format('H:i') : null,
            'elapsed_seconds' => $elapsedSeconds,
            'remaining_seconds' => max(0, $remainingSeconds),
            'is_time_up' => $isTimeUp,
            'rental_amount' => $activeSession->rental_amount,
            'fnb_amount' => $activeSession->fnb_amount,
            'total_amount' => $activeSession->total_amount,
        ];

        $recentRequests = CustomerRequest::where('tv_id', $tv->id)
            ->where('created_at', '>=', $activeSession->start_time)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'type' => $req->type,
                    'payload' => $req->payload,
                    'status' => $req->status,
                    'time' => $req->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'expired' => false,
            'tv' => [
                'id' => $tv->id,
                'name' => $tv->name,
                'type' => $tv->type,
                'status' => $tv->status,
            ],
            'session' => $sessionInfo,
            'requests' => $recentRequests,
        ]);
    }
}
