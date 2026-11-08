<?php

namespace App\Services;

use App\Models\PlaySession;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    /**
     * Hitung rincian tagihan terbaru untuk sesi (mendukung prepaid & postpaid).
     *
     * @return array{rental_amount: int, fnb_amount: int, grand_total: int}
     */
    public function calculateSessionTotals(PlaySession $session, ?int $customRentalAmount = null): array
    {
        $session->loadMissing(['tv', 'sessionOrders.product']);

        $now = Carbon::now();
        $rentalAmount = (int) $session->rental_amount;

        if ($session->billing_type === 'postpaid') {
            $durationMinutes = max(1, $session->start_time->diffInMinutes($now));
            $blokWaktu = ceil($durationMinutes / 5);
            $tarifPerLimaMenit = ($session->tv->price_per_hour / 60) * 5;

            if ($customRentalAmount !== null) {
                $rentalAmount = $customRentalAmount;
            } else {
                $rentalAmount = (int) ($blokWaktu * $tarifPerLimaMenit);
            }
        } elseif ($customRentalAmount !== null) {
            $rentalAmount = $customRentalAmount;
        }

        $fnbAmount = $session->sessionOrders->isNotEmpty()
            ? (int) $session->sessionOrders->sum('subtotal')
            : (int) $session->fnb_amount;
        $grandTotal = max(1, $rentalAmount + $fnbAmount);

        return [
            'rental_amount' => $rentalAmount,
            'fnb_amount' => $fnbAmount,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * Buat Snap Token Midtrans untuk PlaySession.
     *
     * @return array{snap_token: string, order_id: string, rental_amount: int, fnb_amount: int, grand_total: int}
     */
    public function createSnapTransaction(PlaySession $session, ?int $customRentalAmount = null): array
    {
        $totals = $this->calculateSessionTotals($session, $customRentalAmount);

        $orderId = 'RENTAL-'.$session->id.'-'.time();

        $itemDetails = [];

        if ($totals['rental_amount'] > 0) {
            $itemDetails[] = [
                'id' => 'TV-'.$session->tv_id,
                'price' => $totals['rental_amount'],
                'quantity' => 1,
                'name' => substr('Sewa '.($session->tv->name ?? 'Unit PS'), 0, 50),
            ];
        }

        foreach ($session->sessionOrders as $order) {
            if ($order->subtotal > 0 && $order->quantity > 0) {
                $unitPrice = (int) round($order->subtotal / $order->quantity);
                $itemDetails[] = [
                    'id' => 'FNB-'.$order->product_id,
                    'price' => $unitPrice,
                    'quantity' => (int) $order->quantity,
                    'name' => substr($order->product->name ?? 'Item F&B', 0, 50),
                ];
            }
        }

        // Fallback jika itemDetails kosong atau jumlahnya tidak sama dengan grand_total
        $itemsSum = collect($itemDetails)->sum(fn (array $item) => $item['price'] * $item['quantity']);
        if (empty($itemDetails) || $itemsSum !== $totals['grand_total']) {
            $itemDetails = [
                [
                    'id' => 'SESSION-'.$session->id,
                    'price' => $totals['grand_total'],
                    'quantity' => 1,
                    'name' => substr('Tagihan Rental '.($session->tv->name ?? 'PS'), 0, 50),
                ],
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totals['grand_total'],
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $session->customer_name ?: ('Pelanggan '.($session->tv->name ?? 'PS')),
            ],
            // Prioritaskan pembayaran berbasis QR (QRIS / GoPay / ShopeePay) di layar kasir
            'enabled_payments' => ['other_qris', 'gopay', 'shopeepay'],
        ];

        $snapToken = Snap::getSnapToken($params);

        $session->update([
            'midtrans_order_id' => $orderId,
            'snap_token' => $snapToken,
        ]);

        return [
            'snap_token' => $snapToken,
            'order_id' => $orderId,
            'rental_amount' => $totals['rental_amount'],
            'fnb_amount' => $totals['fnb_amount'],
            'grand_total' => $totals['grand_total'],
        ];
    }

    /**
     * Selesaikan sesi secara idempoten (aman dipanggil dari Controller maupun Webhook).
     */
    public function finalizeSessionCheckout(
        PlaySession $session,
        string $paymentMethod,
        ?string $notes = null,
        ?int $customRentalAmount = null
    ): int {
        $session->loadMissing(['tv', 'sessionOrders.product']);

        // Idempotency guard: Jika sesi sudah completed, langsung kembalikan total_amount yang tersimpan
        if ($session->status === 'completed') {
            return (int) $session->total_amount;
        }

        $totals = $this->calculateSessionTotals($session, $customRentalAmount);
        $now = Carbon::now();

        DB::transaction(function () use ($session, $now, $totals, $paymentMethod, $notes) {
            // Lock baris sesi untuk mencegah race condition antara Webhook dan Submit Form
            $lockedSession = PlaySession::where('id', $session->id)->lockForUpdate()->first();
            if (! $lockedSession || $lockedSession->status === 'completed') {
                return;
            }

            $lockedSession->update([
                'status' => 'completed',
                'end_time' => $now,
                'rental_amount' => $totals['rental_amount'],
                'fnb_amount' => $totals['fnb_amount'],
                'total_amount' => $totals['grand_total'],
                'payment_method' => $paymentMethod,
                'notes' => $notes ?? $lockedSession->notes,
            ]);

            // Kembalikan stok untuk produk addon
            foreach ($session->sessionOrders as $order) {
                if ($order->product && $order->product->category === 'addon') {
                    $order->product->increment('stock', $order->quantity);
                }
            }

            // Set TV menjadi available dan matikan buzzer
            $session->tv->update([
                'status' => 'available',
                'is_buzzer_on' => false,
            ]);

            // Update statistik shift aktif
            $activeShift = Shift::where('status', 'active')->latest()->first();
            if ($activeShift) {
                $activeShift->increment('total_revenue', $totals['grand_total']);
                $activeShift->increment('transactions_count', 1);
            }
        });

        return $totals['grand_total'];
    }
}
