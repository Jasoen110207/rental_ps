<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Payroll extends Model
{
    use HasFactory;

    public const SHIFT_RATE = 50000;

    protected $fillable = [
        'user_id',
        'period_month',
        'base_salary',
        'bonus',
        'deductions',
        'total_revenue',
        'work_hours',
        'work_days',
        'net_salary',
        'status',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'base_salary' => 'integer',
            'bonus' => 'integer',
            'deductions' => 'integer',
            'total_revenue' => 'integer',
            'work_hours' => 'integer',
            'work_days' => 'integer',
            'net_salary' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sinkronisasi otomatis data penggajian kasir berdasarkan jumlah absen (shift) pada bulan terkait.
     */
    public static function syncForUser(User $user, ?string $periodMonth = null): ?self
    {
        if ($user->role !== 'kasir') {
            return null;
        }

        $now = Carbon::now();
        $periodMonth ??= $now->format('Y-m');

        $monthStart = Carbon::createFromFormat('Y-m', $periodMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $periodMonth)->endOfMonth();

        $shifts = Shift::where('user_id', $user->id)
            ->whereBetween('start_time', [$monthStart, $monthEnd])
            ->get();

        $totalShifts = $shifts->count();

        $totalRevenue = (int) PlaySession::where('user_id', $user->id)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total_amount');

        $workHours = (int) Shift::where('user_id', $user->id)
            ->whereBetween('start_time', [$monthStart, $monthEnd])
            ->sum(DB::raw('TIMESTAMPDIFF(HOUR, start_time, COALESCE(end_time, NOW()))'));

        $totalShiftSalary = $totalShifts * self::SHIFT_RATE;

        $existingPayroll = self::where('user_id', $user->id)
            ->where('period_month', $periodMonth)
            ->first();

        $bonus = $existingPayroll ? (int) $existingPayroll->bonus : 0;
        $deductions = $existingPayroll ? (int) $existingPayroll->deductions : 0;
        $netSalary = max(0, $totalShiftSalary + $bonus - $deductions);
        $status = $existingPayroll ? $existingPayroll->status : 'pending';

        return self::updateOrCreate(
            [
                'user_id' => $user->id,
                'period_month' => $periodMonth,
            ],
            [
                'base_salary' => $totalShiftSalary,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'total_revenue' => $totalRevenue,
                'work_hours' => $workHours,
                'work_days' => $totalShifts,
                'net_salary' => $netSalary,
                'status' => $status,
                'notes' => $existingPayroll?->notes ?? "Perhitungan otomatis {$totalShifts} shift x Rp ".number_format(self::SHIFT_RATE, 0, ',', '.'),
            ]
        );
    }
}
