<?php

namespace App\Services;

use App\Models\PlaySession;
use App\Models\Tv;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PlaySessionService
{
    /**
     * Memulai sesi bermain baru pada TV.
     *
     * @param  string  $billingType  ('prepaid' atau 'postpaid')
     *
     * @throws Exception
     */
    public function startSession(Tv $tv, User $kasir, string $billingType, ?int $durationMinutes = null): PlaySession
    {
        if ($tv->status !== 'available') {
            throw new Exception("TV '{$tv->name}' sedang tidak tersedia (status: {$tv->status}).");
        }

        if ($billingType === 'prepaid' && (! $durationMinutes || $durationMinutes < 1)) {
            throw new Exception('Durasi wajib diisi untuk billing prepaid.');
        }

        return DB::transaction(function () use ($tv, $kasir, $billingType, $durationMinutes) {
            // Ubah status TV menjadi playing
            $tv->update(['status' => 'playing']);

            $startTime = Carbon::now();
            $endTime = ($billingType === 'prepaid' && $durationMinutes)
                ? $startTime->copy()->addMinutes($durationMinutes)
                : null;

            // Buat sesi bermain baru
            return PlaySession::create([
                'tv_id' => $tv->id,
                'user_id' => $kasir->id,
                'billing_type' => $billingType,
                'duration_minutes' => $durationMinutes,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'active',
                'total_amount' => 0,
            ]);
        });
    }

    /**
     * Mengakhiri sesi bermain, mengkalkulasi durasi waktu, biaya TV, pesanan F&B, dan total biaya.
     *
     * @throws Exception
     */
    public function endSession(PlaySession $session): PlaySession
    {
        if ($session->status !== 'active') {
            throw new Exception('Sesi bermain ini sudah tidak aktif atau sudah selesai.');
        }

        return DB::transaction(function () use ($session) {
            $endTime = Carbon::now();
            $startTime = Carbon::parse($session->start_time);

            // Hitung durasi bermain dalam menit
            $diffInMinutes = max(1, $startTime->diffInMinutes($endTime));
            $pricePerHour = $session->tv->price_per_hour;

            // Biaya rental TV: (menit / 60) * harga per jam
            $playCost = (int) round(($diffInMinutes / 60) * $pricePerHour);

            // Hitung total biaya pesanan F&B dari session_orders
            $foodCost = (int) $session->sessionOrders()->sum('subtotal');

            // Total tagihan keseluruhan
            $totalAmount = $playCost + $foodCost;

            // Update sesi bermain
            $session->update([
                'end_time' => $endTime,
                'status' => 'completed',
                'total_amount' => $totalAmount,
            ]);

            // Kembalikan status TV menjadi available
            $session->tv->update(['status' => 'available']);

            return $session->fresh(['tv', 'sessionOrders.product']);
        });
    }
}
