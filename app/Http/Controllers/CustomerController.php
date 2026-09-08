<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tv;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index($tvId)
    {
        $tv = Tv::findOrFail($tvId);

        $activeSession = PlaySession::with('sessionOrders.product')
            ->where('tv_id', $tv->id)
            ->where('status', 'active')
            ->first();

        $products = Product::where('is_available', true)
            ->where('stock', '>', 0)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $requests = CustomerRequest::where('tv_id', $tv->id)
            ->where('created_at', '>=', Carbon::now()->subHours(12))
            ->orderBy('created_at', 'desc')
            ->get();

        $storeName = Setting::get('store_name', 'TambahBang Rental PS');

        return view('customer.index', compact('tv', 'activeSession', 'products', 'requests', 'storeName'));
    }

    public function requestAddTime(Request $request, $tvId)
    {
        $tv = Tv::findOrFail($tvId);

        $validated = $request->validate([
            'duration_hours' => 'required|numeric|min:0.5|max:12',
            'note' => 'nullable|string|max:255',
        ]);

        $hours = (float) $validated['duration_hours'];
        $estimatedPrice = (int) round($hours * $tv->price_per_hour);

        CustomerRequest::create([
            'tv_id' => $tv->id,
            'type' => 'add_time',
            'payload' => [
                'duration_hours' => $hours,
                'price' => $estimatedPrice,
                'note' => $validated['note'] ?? ('Tambah ' . ($hours == 1 ? '1 Jam' : ($hours . ' Jam'))),
            ],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permintaan tambah waktu berhasil dikirim ke kasir! Mohon tunggu konfirmasi.');
    }

    public function requestFood(Request $request, $tvId)
    {
        $tv = Tv::findOrFail($tvId);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:20',
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

        // Tolak item yang stoknya tidak mencukupi (sinkron ke DB produk)
        foreach ($orderItems as $oi) {
            $product = Product::find($oi['product_id']);
            if (! $product || $product->stock < $oi['quantity']) {
                return back()->with('error', 'Stok "' . ($product->name ?? 'menu') . '" tidak mencukupi (sisa ' . ($product->stock ?? 0) . ').');
            }
        }

        CustomerRequest::create([
            'tv_id' => $tv->id,
            'type' => 'order_food',
            'payload' => [
                'items' => $orderItems,
                'total_price' => $totalPrice,
                'note' => $validated['note'] ?? null,
            ],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pesanan makanan & minuman berhasil dikirim ke kasir!');
    }

    /**
     * Halaman order cepat (satu menu sekali klik) — dinamis per meja.
     * Bisa diakses via /customer/order?tv_id=.. agar sinkron ke database.
     */
    public function order(Request $request)
    {
        $tv = null;
        if ($request->filled('tv_id')) {
            $tv = Tv::find($request->get('tv_id'));
        }
        if (! $tv) {
            // Prioritas: meja yang sedang ada sesi aktif, lalu meja pertama
            $activeTvId = PlaySession::where('status', 'active')->orderBy('start_time')->value('tv_id');
            $tv = $activeTvId ? Tv::find($activeTvId) : Tv::orderBy('id')->firstOrFail();
        }

        $activeSession = PlaySession::with('sessionOrders.product')
            ->where('tv_id', $tv->id)
            ->where('status', 'active')
            ->first();

        $products = Product::where('is_available', true)
            ->where('stock', '>', 0)
            ->orderBy('category')
            ->orderBy('name')
            ->take(8)
            ->get();

        $requests = CustomerRequest::where('tv_id', $tv->id)
            ->where('created_at', '>=', Carbon::now()->subHours(12))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $tvs = Tv::orderBy('id')->get();
        $storeName = Setting::get('store_name', 'TambahBang Rental PS');

        return view('customer.order', compact('tv', 'activeSession', 'products', 'requests', 'tvs', 'storeName'));
    }

    /**
     * Panggil kasir ke meja (service call). Disetujui kasir tanpa mengubah tagihan.
     */
    public function callCashier(Request $request, $tvId)
    {
        $tv = Tv::findOrFail($tvId);

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

        CustomerRequest::create([
            'tv_id' => $tv->id,
            'type' => 'service_call',
            'payload' => [
                'note' => $validated['note'] ?? 'Pelanggan memanggil kasir ke meja.',
            ],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Kasir sedang menuju ke meja ' . $tv->name . '!');
    }

    public function apiStatus($tvId)
    {
        $tv = Tv::findOrFail($tvId);

        $activeSession = PlaySession::with('sessionOrders.product')
            ->where('tv_id', $tv->id)
            ->where('status', 'active')
            ->first();

        $now = Carbon::now();
        $sessionInfo = null;

        if ($activeSession) {
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
        }

        $recentRequests = CustomerRequest::where('tv_id', $tv->id)
            ->where('created_at', '>=', Carbon::now()->subHours(12))
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
