<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\SessionOrder;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Tv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * KasirController — seluruh halaman kasir tersambung ke database yang sama
 * dengan admin (tvs, play_sessions, products, customer_requests, shifts).
 * Logika DB disalin dari controller admin, redirect diarahkan ke route kasir.*.
 */
class KasirController extends Controller
{
    /* ================= DASHBOARD ================= */
    public function dashboard()
    {
        $tvs = Tv::with([
            'playSessions' => fn ($q) => $q->where('status', 'active')->with('sessionOrders.product'),
            'customerRequests' => fn ($q) => $q->where('status', 'pending'),
        ])->orderBy('id')->get();

        $now = Carbon::now();
        $totalUnits = $tvs->count();
        $availableUnits = $tvs->where('status', 'available')->count();
        $playingUnits = $tvs->where('status', 'playing')->count();
        $almostFinishedUnits = 0;
        $timeUpUnits = 0;

        foreach ($tvs as $tv) {
            $s = $tv->playSessions->first();
            if ($s && $s->billing_type === 'prepaid' && $s->end_time) {
                if ($s->end_time->isPast()) {
                    $timeUpUnits++;
                } elseif ($s->end_time->diffInMinutes($now) <= 10) {
                    $almostFinishedUnits++;
                }
            }
        }

        $pendingRequestsCount = CustomerRequest::where('status', 'pending')->count();
        $activeShift = Shift::where('status', 'active')->latest()->first();
        $products = Product::where('is_available', true)->get();

        return view('kasir.dashboard', compact(
            'tvs', 'totalUnits', 'availableUnits', 'playingUnits',
            'almostFinishedUnits', 'timeUpUnits', 'pendingRequestsCount',
            'activeShift', 'products'
        ));
    }

    public function apiStatus()
    {
        // Samakan persis format JSON admin agar polling JS kompatibel
        $tvs = Tv::with([
            'playSessions' => fn ($q) => $q->where('status', 'active')->with('sessionOrders.product'),
            'customerRequests' => fn ($q) => $q->where('status', 'pending'),
        ])->orderBy('id')->get();

        $now = Carbon::now();
        $tvData = [];
        $availableCount = 0;
        $playingCount = 0;
        $almostFinishedCount = 0;
        $timeUpCount = 0;

        foreach ($tvs as $tv) {
            $activeSession = $tv->playSessions->first();
            $sessionInfo = null;

            if ($activeSession) {
                $elapsedSeconds = $activeSession->start_time->diffInSeconds($now);
                $remainingSeconds = 0;
                $isAlmostFinished = false;
                $isTimeUp = false;
                $rentalCost = $activeSession->rental_amount;

                if ($activeSession->billing_type === 'prepaid' && $activeSession->end_time) {
                    if ($activeSession->end_time->isPast()) {
                        $isTimeUp = true;
                        $timeUpCount++;
                    } else {
                        $remainingSeconds = $now->diffInSeconds($activeSession->end_time, false);
                        if ($remainingSeconds <= 600) {
                            $isAlmostFinished = true;
                            $almostFinishedCount++;
                        }
                    }
                } elseif ($activeSession->billing_type === 'postpaid') {
                    $elapsedHours = max(1, (int) ceil($elapsedSeconds / 3600));
                    $rentalCost = (int) ($elapsedHours * $tv->price_per_hour);
                }

                $fnbCost = $activeSession->sessionOrders->sum('subtotal');
                $sessionInfo = [
                    'id' => $activeSession->id,
                    'billing_type' => $activeSession->billing_type,
                    'start_time' => $activeSession->start_time->format('H:i'),
                    'end_time' => $activeSession->end_time ? $activeSession->end_time->format('H:i') : null,
                    'elapsed_seconds' => $elapsedSeconds,
                    'remaining_seconds' => max(0, $remainingSeconds),
                    'is_almost_finished' => $isAlmostFinished,
                    'is_time_up' => $isTimeUp,
                    'rental_cost' => $rentalCost,
                    'fnb_cost' => $fnbCost,
                    'total_cost' => $rentalCost + $fnbCost,
                    'orders' => $activeSession->sessionOrders->map(fn ($o) => [
                        'name' => $o->product ? $o->product->name : 'Item',
                        'quantity' => $o->quantity,
                        'subtotal' => $o->subtotal,
                    ]),
                ];
            }

            if ($tv->status === 'available') {
                $availableCount++;
            } elseif ($tv->status === 'playing') {
                $playingCount++;
            }

            $tvData[] = [
                'id' => $tv->id,
                'name' => $tv->name,
                'type' => $tv->type,
                'price_per_hour' => $tv->price_per_hour,
                'status' => $tv->status,
                'is_buzzer_on' => (bool) $tv->is_buzzer_on,
                'pending_requests' => $tv->customerRequests->count(),
                'active_session' => $sessionInfo,
            ];
        }

        $pendingRequests = CustomerRequest::with('tv')->where('status', 'pending')
            ->orderBy('created_at', 'desc')->get()
            ->map(fn ($req) => [
                'id' => $req->id,
                'tv_name' => $req->tv ? $req->tv->name : 'Meja',
                'tv_id' => $req->tv_id,
                'type' => $req->type,
                'payload' => $req->payload,
                'time_ago' => $req->created_at->diffForHumans(),
            ]);

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => $tvs->count(),
                'available' => $availableCount,
                'playing' => $playingCount,
                'almost_finished' => $almostFinishedCount,
                'time_up' => $timeUpCount,
                'pending_requests_count' => $pendingRequests->count(),
            ],
            'tvs' => $tvData,
            'pending_requests' => $pendingRequests,
            'server_time' => Carbon::now()->isoFormat('dddd, D MMMM YYYY • HH:mm:ss'),
        ]);
    }

    /* ================= RENTAL ACTIONS ================= */
    public function startRental(Request $request)
    {
        $validated = $request->validate([
            'tv_id' => 'required|exists:tvs,id',
            'billing_type' => 'required|in:prepaid,postpaid',
            'duration_hours' => 'nullable|numeric|min:0.5',
            'notes' => 'nullable|string',
        ]);

        $tv = Tv::findOrFail($validated['tv_id']);
        if ($tv->status !== 'available') {
            return back()->with('error', 'Unit ' . $tv->name . ' sedang tidak tersedia!');
        }

        $startTime = Carbon::now();
        $endTime = null;
        $rentalAmount = 0;
        if ($validated['billing_type'] === 'prepaid') {
            $hours = (float) ($validated['duration_hours'] ?? 1);
            $endTime = $startTime->copy()->addMinutes((int) round($hours * 60));
            $rentalAmount = (int) round($hours * $tv->price_per_hour);
        }

        DB::transaction(function () use ($tv, $validated, $startTime, $endTime, $rentalAmount) {
            PlaySession::create([
                'tv_id' => $tv->id,
                'user_id' => Auth::id() ?? 1,
                'billing_type' => $validated['billing_type'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'active',
                'rental_amount' => $rentalAmount,
                'fnb_amount' => 0,
                'total_amount' => $rentalAmount,
                'payment_method' => 'cash',
                'notes' => $validated['notes'] ?? null,
            ]);
            $tv->update(['status' => 'playing', 'is_buzzer_on' => false]);
        });

        return back()->with('success', 'Rental berhasil dimulai untuk ' . $tv->name);
    }

    public function extendRental(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'added_hours' => 'nullable|numeric|min:0.25',
            'added_minutes' => 'nullable|numeric|min:5',
        ]);

        $session = PlaySession::with('tv')->findOrFail($sessionId);
        if ($session->status !== 'active') {
            return back()->with('error', 'Sesi rental tidak aktif!');
        }

        $addedMinutes = !empty($validated['added_hours'])
            ? (int) round($validated['added_hours'] * 60)
            : (int) ($validated['added_minutes'] ?? 60);
        $additionalFee = (int) round(($addedMinutes / 60) * $session->tv->price_per_hour);

        DB::transaction(function () use ($session, $addedMinutes, $additionalFee) {
            $base = $session->end_time && $session->end_time->isFuture() ? $session->end_time : Carbon::now();
            // Jika sudah lewat, perpanjang dari sekarang
            if ($session->end_time && $session->end_time->isPast()) {
                $base = Carbon::now();
            }
            $newRental = $session->rental_amount + $additionalFee;
            $session->update([
                'billing_type' => 'prepaid',
                'end_time' => $base->copy()->addMinutes($addedMinutes),
                'rental_amount' => $newRental,
                'total_amount' => $newRental + $session->fnb_amount,
            ]);
            $session->tv->update(['is_buzzer_on' => false]);
        });

        return back()->with('success', 'Waktu +' . $addedMinutes . ' menit untuk ' . $session->tv->name);
    }

    public function addFnb(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $session = PlaySession::findOrFail($sessionId);
        if ($session->status !== 'active') {
            return back()->with('error', 'Sesi rental tidak aktif!');
        }

        DB::transaction(function () use ($session, $validated) {
            $added = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $item['quantity'] > 0) {
                    $subtotal = $product->price * $item['quantity'];
                    $added += $subtotal;
                    if ($product->stock >= $item['quantity']) {
                        $product->decrement('stock', $item['quantity']);
                    }
                    SessionOrder::create([
                        'play_session_id' => $session->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }
            $newFnb = $session->fnb_amount + $added;
            $session->update(['fnb_amount' => $newFnb, 'total_amount' => $session->rental_amount + $newFnb]);
        });

        return back()->with('success', 'F&B ditambahkan ke ' . $session->tv->name);
    }

    public function checkout(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer,other',
            'notes' => 'nullable|string',
            'custom_rental_amount' => 'nullable|numeric',
        ]);

        $session = PlaySession::with(['tv', 'sessionOrders.product'])->findOrFail($sessionId);
        $now = Carbon::now();
        $rentalAmount = $session->rental_amount;

        if ($session->billing_type === 'postpaid') {
            $durationMinutes = max(1, $session->start_time->diffInMinutes($now));
            $durationHours = (int) ceil($durationMinutes / 60);
            $rentalAmount = isset($validated['custom_rental_amount']) && $validated['custom_rental_amount'] !== ''
                ? (int) $validated['custom_rental_amount']
                : (int) ($durationHours * $session->tv->price_per_hour);
        } elseif (isset($validated['custom_rental_amount']) && $validated['custom_rental_amount'] !== '') {
            $rentalAmount = (int) $validated['custom_rental_amount'];
        }

        $fnbAmount = $session->sessionOrders->sum('subtotal');
        $grandTotal = $rentalAmount + $fnbAmount;

        DB::transaction(function () use ($session, $now, $rentalAmount, $fnbAmount, $grandTotal, $validated) {
            $session->update([
                'status' => 'completed',
                'end_time' => $now,
                'rental_amount' => $rentalAmount,
                'fnb_amount' => $fnbAmount,
                'total_amount' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? $session->notes,
            ]);
            $session->tv->update(['status' => 'available', 'is_buzzer_on' => false]);
            $activeShift = Shift::where('status', 'active')->latest()->first();
            if ($activeShift) {
                $activeShift->increment('total_revenue', $grandTotal);
                $activeShift->increment('transactions_count', 1);
            }
        });

        return redirect()->route('kasir.dashboard')
            ->with('success', 'Checkout ' . $session->tv->name . ' lunas Rp ' . number_format($grandTotal, 0, ',', '.'));
    }

    public function toggleBuzzer($tvId)
    {
        $tv = Tv::findOrFail($tvId);
        $tv->update(['is_buzzer_on' => !$tv->is_buzzer_on]);

        return back()->with('success', 'Buzzer ' . $tv->name . ' ' . ($tv->is_buzzer_on ? 'dimatikan' : 'diaktifkan'));
    }

    /* ================= POS / MENU ================= */
    public function pos(Request $request)
    {
        $activeSessions = PlaySession::with(['tv', 'sessionOrders.product'])
            ->where('status', 'active')->orderBy('start_time')->get();

        $selected = null;
        if ($request->filled('session_id')) {
            $selected = $activeSessions->firstWhere('id', (int) $request->get('session_id'));
        }
        $selected = $selected ?? $activeSessions->first();

        $tvs = Tv::orderBy('id')->get();
        $availableUnits = $tvs->where('status', 'available');
        $products = Product::where('is_available', true)->orderBy('name')->get();

        return view('kasir.pos', compact('activeSessions', 'selected', 'tvs', 'availableUnits', 'products'));
    }

    public function menu(Request $request)
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search', '');
        $query = Product::query();
        if ($category !== 'all') {
            $query->where('category', $category);
        }
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }
        $products = $query->orderBy('name')->get();
        $activeSessions = PlaySession::with('tv')->where('status', 'active')->get();

        return view('kasir.menu', compact('products', 'activeSessions', 'category', 'search'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'target_type' => 'required|in:session,direct',
            'play_session_id' => 'required_if:target_type,session|nullable|exists:play_sessions,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|in:cash,qris,transfer,other',
        ]);

        DB::transaction(function () use ($validated) {
            $total = 0;
            if ($validated['target_type'] === 'session') {
                $session = PlaySession::findOrFail($validated['play_session_id']);
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product && $item['quantity'] > 0) {
                        $subtotal = $product->price * $item['quantity'];
                        $total += $subtotal;
                        if ($product->stock >= $item['quantity']) {
                            $product->decrement('stock', $item['quantity']);
                        }
                        SessionOrder::create([
                            'play_session_id' => $session->id,
                            'product_id' => $product->id,
                            'quantity' => $item['quantity'],
                            'subtotal' => $subtotal,
                        ]);
                    }
                }
                $newFnb = $session->fnb_amount + $total;
                $session->update(['fnb_amount' => $newFnb, 'total_amount' => $session->rental_amount + $newFnb]);
            } else {
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product && $item['quantity'] > 0) {
                        $total += $product->price * $item['quantity'];
                        if ($product->stock >= $item['quantity']) {
                            $product->decrement('stock', $item['quantity']);
                        }
                    }
                }
                $activeShift = Shift::where('status', 'active')->latest()->first();
                if ($activeShift) {
                    $activeShift->increment('total_revenue', $total);
                    $activeShift->increment('transactions_count', 1);
                }
            }
        });

        return back()->with('success', 'Pesanan F&B berhasil diproses!');
    }

    /* ================= REQUESTS ================= */
    public function requests(Request $request)
    {
        $filter = $request->get('tab', 'pending');
        $query = CustomerRequest::with(['tv.playSessions' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('created_at', 'desc');
        if (in_array($filter, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $filter);
        }
        $requests = $query->paginate(12)->withQueryString();
        $counts = [
            'all' => CustomerRequest::count(),
            'pending' => CustomerRequest::where('status', 'pending')->count(),
            'approved' => CustomerRequest::where('status', 'approved')->count(),
            'rejected' => CustomerRequest::where('status', 'rejected')->count(),
        ];

        return view('kasir.request', compact('requests', 'filter', 'counts'));
    }

    public function approveRequest($id)
    {
        $customerRequest = CustomerRequest::with('tv.playSessions')->findOrFail($id);
        if ($customerRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan sudah diproses sebelumnya!');
        }

        $activeSession = PlaySession::where('tv_id', $customerRequest->tv_id)
            ->where('status', 'active')->first();

        DB::transaction(function () use ($customerRequest, $activeSession) {
            $payload = $customerRequest->payload ?? [];
            if ($customerRequest->type === 'add_time' && $activeSession) {
                $hours = (float) ($payload['duration_hours'] ?? 1);
                $addedMinutes = (int) round($hours * 60);
                $addedFee = (int) round($hours * $activeSession->tv->price_per_hour);
                $base = $activeSession->end_time && $activeSession->end_time->isFuture()
                    ? $activeSession->end_time : Carbon::now();
                $newRental = $activeSession->rental_amount + $addedFee;
                $activeSession->update([
                    'billing_type' => 'prepaid',
                    'end_time' => $base->copy()->addMinutes($addedMinutes),
                    'rental_amount' => $newRental,
                    'total_amount' => $newRental + $activeSession->fnb_amount,
                ]);
                $activeSession->tv->update(['is_buzzer_on' => false]);
            } elseif ($customerRequest->type === 'order_food' && $activeSession && !empty($payload['items'])) {
                $addedFnb = 0;
                foreach ($payload['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $qty = (int) ($item['quantity'] ?? 1);
                        $subtotal = $product->price * $qty;
                        $addedFnb += $subtotal;
                        if ($product->stock >= $qty) {
                            $product->decrement('stock', $qty);
                        }
                        SessionOrder::create([
                            'play_session_id' => $activeSession->id,
                            'product_id' => $product->id,
                            'quantity' => $qty,
                            'subtotal' => $subtotal,
                        ]);
                    }
                }
                $newFnb = $activeSession->fnb_amount + $addedFnb;
                $activeSession->update(['fnb_amount' => $newFnb, 'total_amount' => $activeSession->rental_amount + $newFnb]);
            }
            $customerRequest->update(['status' => 'approved']);
        });

        return back()->with('success', 'Permintaan disetujui!');
    }

    public function rejectRequest($id)
    {
        $customerRequest = CustomerRequest::findOrFail($id);
        if ($customerRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan sudah diproses sebelumnya!');
        }
        $customerRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan ditolak.');
    }

    /* ================= TRANSAKSI ================= */
    public function transaksi(Request $request)
    {
        $query = PlaySession::with(['tv', 'user', 'sessionOrders.product'])
            ->where('status', 'completed')->orderBy('end_time', 'desc');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('tv', fn ($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('tv_id')) {
            $query->where('tv_id', $request->get('tv_id'));
        }
        if ($request->filled('billing_type')) {
            $query->where('billing_type', $request->get('billing_type'));
        }

        $transactions = $query->paginate(10)->withQueryString();
        $tvs = Tv::orderBy('name')->get();
        $totalRevenue = PlaySession::where('status', 'completed')->sum('total_amount');
        $totalRentalRevenue = PlaySession::where('status', 'completed')->sum('rental_amount');
        $totalFnbRevenue = PlaySession::where('status', 'completed')->sum('fnb_amount');
        $preview = (clone $query)->first();

        return view('kasir.transaksi', compact(
            'transactions', 'tvs', 'totalRevenue', 'totalRentalRevenue', 'totalFnbRevenue', 'preview'
        ));
    }

    /* ================= SHIFT ================= */
    public function sift()
    {
        $activeShift = Shift::with('user')->where('status', 'active')->latest()->first();
        $ongoingRentalsCount = PlaySession::where('status', 'active')->count();
        $liveShiftRevenue = 0;
        $liveShiftTransactions = 0;
        if ($activeShift) {
            $liveShiftRevenue = PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)->sum('total_amount');
            $liveShiftTransactions = PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)->count();
        }
        $shiftHistory = Shift::with('user')->orderBy('created_at', 'desc')->paginate(8);
        $activeSessions = PlaySession::with('tv')->where('status', 'active')->get();

        return view('kasir.sift', compact(
            'activeShift', 'ongoingRentalsCount', 'liveShiftRevenue',
            'liveShiftTransactions', 'shiftHistory', 'activeSessions'
        ));
    }

    public function startShift(Request $request)
    {
        $validated = $request->validate([
            'starting_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Shift::where('status', 'active')->update(['status' => 'closed', 'end_time' => Carbon::now()]);
        Shift::create([
            'user_id' => Auth::id() ?? 1,
            'start_time' => Carbon::now(),
            'starting_cash' => $validated['starting_cash'],
            'total_revenue' => 0,
            'transactions_count' => 0,
            'notes' => $validated['notes'] ?? 'Shift baru dibuka.',
            'status' => 'active',
        ]);

        return back()->with('success', 'Shift baru berhasil dimulai.');
    }

    public function endShift(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $validated = $request->validate(['notes' => 'nullable|string']);
        $revenue = PlaySession::where('status', 'completed')
            ->where('end_time', '>=', $shift->start_time)->sum('total_amount');
        $txCount = PlaySession::where('status', 'completed')
            ->where('end_time', '>=', $shift->start_time)->count();
        $shift->update([
            'end_time' => Carbon::now(),
            'total_revenue' => $revenue,
            'transactions_count' => $txCount,
            'notes' => $validated['notes'] ?? $shift->notes,
            'status' => 'closed',
        ]);

        return back()->with('success', 'Shift berhasil ditutup dan diserahkan.');
    }

    /* ================= UNIT ================= */
    public function unit(Request $request)
    {
        $units = Tv::with(['playSessions' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('id')->get();
        $selected = $units->firstWhere('id', (int) $request->get('unit_id')) ?? $units->first();

        return view('kasir.unit', compact('units', 'selected'));
    }

    public function toggleUnit($id)
    {
        $tv = Tv::findOrFail($id);
        $tv->update(['status' => $tv->status === 'maintenance' ? 'available' : 'maintenance']);

        return back()->with('success', 'Status ' . $tv->name . ' menjadi ' . $tv->status);
    }

    /* ================= SETTING ================= */
    public function setting()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('kasir.setting', compact('settings'));
    }

    public function updateSetting(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
