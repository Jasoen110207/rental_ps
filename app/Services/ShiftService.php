<?php

namespace App\Services;

use App\Models\PlaySession;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ShiftService
{
    /**
     * Memulai shift baru untuk kasir.
     */
    public function startShift(User $kasir): Shift
    {
        $hasActiveShift = Shift::where('user_id', $kasir->id)
            ->whereIn('status', ['active'])
            ->whereNull('end_time')
            ->exists();

        if ($hasActiveShift) {
            throw new Exception('Kasir sudah memiliki shift aktif.');
        }

        return Shift::create([
            'user_id' => $kasir->id,
            'start_time' => Carbon::now(),
            'status' => 'active',
            'total_revenue' => 0,
        ]);
    }

    /**
     * Mengakhiri shift kasir & mengkalkulasi total revenue selama shift.
     */
    public function endShift(User $kasir): Shift
    {
        $shift = Shift::where('user_id', $kasir->id)
            ->where('status', 'active')
            ->whereNull('end_time')
            ->latest()
            ->first();

        if (! $shift) {
            throw new Exception('Kasir tidak memiliki shift aktif untuk diakhiri.');
        }

        return DB::transaction(function () use ($shift, $kasir) {
            $endTime = Carbon::now();

            $revenue = (int) PlaySession::where('user_id', $kasir->id)
                ->where('status', 'completed')
                ->whereBetween('updated_at', [$shift->start_time, $endTime])
                ->sum('total_amount');

            $shift->update([
                'end_time' => $endTime,
                'total_revenue' => $revenue,
                'status' => 'closed',
            ]);

            return $shift->fresh('user');
        });
    }
}
