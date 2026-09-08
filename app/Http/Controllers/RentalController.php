<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\SessionOrder;
use App\Models\Shift;
use App\Models\Tv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * Start a new rental session
     */
    public function start(Request $request)
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

        $now = Carbon::now();
        $startTime = $now;
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

            $tv->update([
                'status' => 'playing',
                'is_buzzer_on' => false,
            ]);
        });

        return back()->with('success', 'Rental berhasil dimulai untuk ' . $tv->name);
    }

    /**
     * Extend time for an active prepaid or postpaid session
     */
    public function extend(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'added_hours' => 'nullable|numeric|min:0.25',
            'added_minutes' => 'nullable|numeric|min:5',
        ]);

        $session = PlaySession::with('tv')->findOrFail($sessionId);

        if ($session->status !== 'active') {
            return back()->with('error', 'Sesi rental tidak aktif!');
        }

        $addedMinutes = 0;
        if (!empty($validated['added_hours'])) {
            $addedMinutes = (int) round($validated['added_hours'] * 60);
        } elseif (!empty($validated['added_minutes'])) {
            $addedMinutes = (int) $validated['added_minutes'];
        } else {
            $addedMinutes = 60; // Default 1 hour
        }

        $additionalHours = $addedMinutes / 60;
        $additionalFee = (int) round($additionalHours * $session->tv->price_per_hour);

        DB::transaction(function () use ($session, $addedMinutes, $additionalFee) {
            $baseEndTime = $session->end_time ? $session->end_time : Carbon::now();
            if ($baseEndTime->isPast()) {
                // If already expired, extend from now
                $newEndTime = Carbon::now()->addMinutes($addedMinutes);
            } else {
                $newEndTime = $baseEndTime->copy()->addMinutes($addedMinutes);
            }

            $newRentalAmount = $session->rental_amount + $additionalFee;
            $newTotal = $newRentalAmount + $session->fnb_amount;

            $session->update([
                'billing_type' => 'prepaid',
                'end_time' => $newEndTime,
                'rental_amount' => $newRentalAmount,
                'total_amount' => $newTotal,
            ]);

            // Turn off buzzer if active
            $session->tv->update(['is_buzzer_on' => false]);
        });

        return back()->with('success', 'Waktu berhasil diperpanjang +' . $addedMinutes . ' menit untuk ' . $session->tv->name);
    }

    /**
     * Add F&B to active session
     */
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
            $addedFnbAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $item['quantity'] > 0) {
                    $subtotal = $product->price * $item['quantity'];
                    $addedFnbAmount += $subtotal;

                    // Reduce stock
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

            $newFnbAmount = $session->fnb_amount + $addedFnbAmount;
            $session->update([
                'fnb_amount' => $newFnbAmount,
                'total_amount' => $session->rental_amount + $newFnbAmount,
            ]);
        });

        return back()->with('success', 'Pesanan F&B berhasil ditambahkan ke tagihan ' . $session->tv->name);
    }

    /**
     * Checkout and finalize session
     */
    public function checkout(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer,other',
            'notes' => 'nullable|string',
            'custom_rental_amount' => 'nullable|numeric',
        ]);

        $session = PlaySession::with(['tv', 'sessionOrders.product'])->findOrFail($sessionId);

        $now = Carbon::now();
        $endTime = $now;

        // Calculate rental amount
        $rentalAmount = $session->rental_amount;
        if ($session->billing_type === 'postpaid') {
            $durationMinutes = max(1, $session->start_time->diffInMinutes($now));
            $durationHours = ceil($durationMinutes / 60); // standard hourly billing
            if (isset($validated['custom_rental_amount']) && $validated['custom_rental_amount'] !== '') {
                $rentalAmount = (int) $validated['custom_rental_amount'];
            } else {
                $rentalAmount = (int) ($durationHours * $session->tv->price_per_hour);
            }
        } elseif (isset($validated['custom_rental_amount']) && $validated['custom_rental_amount'] !== '') {
            $rentalAmount = (int) $validated['custom_rental_amount'];
        }

        $fnbAmount = $session->sessionOrders->sum('subtotal');
        $grandTotal = $rentalAmount + $fnbAmount;

        DB::transaction(function () use ($session, $endTime, $rentalAmount, $fnbAmount, $grandTotal, $validated) {
            $session->update([
                'status' => 'completed',
                'end_time' => $endTime,
                'rental_amount' => $rentalAmount,
                'fnb_amount' => $fnbAmount,
                'total_amount' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? $session->notes,
            ]);

            // Set TV available and buzzer off
            $session->tv->update([
                'status' => 'available',
                'is_buzzer_on' => false,
            ]);

            // Update active shift stats
            $activeShift = Shift::where('status', 'active')->latest()->first();
            if ($activeShift) {
                $activeShift->increment('total_revenue', $grandTotal);
                $activeShift->increment('transactions_count', 1);
            }
        });

        return redirect()->route('admin.dashboard')
            ->with('success', 'Checkout berhasil! Pembayaran ' . $session->tv->name . ' sebesar Rp ' . number_format($grandTotal, 0, ',', '.') . ' telah lunas.');
    }

    /**
     * Toggle buzzer
     */
    public function toggleBuzzer($tvId)
    {
        $tv = Tv::findOrFail($tvId);
        $tv->update([
            'is_buzzer_on' => !$tv->is_buzzer_on,
        ]);

        $status = $tv->is_buzzer_on ? 'diaktifkan' : 'dimatikan';
        return back()->with('success', 'Buzzer alarm untuk ' . $tv->name . ' berhasil ' . $status);
    }
}
