<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'starting_cash' => 'integer',
            'total_revenue' => 'integer',
            'reported_revenue' => 'integer',
            'leftover_cash' => 'integer',
            'transactions_count' => 'integer',
        ];
    }

    public static function detectShiftType(?Carbon $time = null): string
    {
        $time ??= Carbon::now();

        return ($time->hour >= 8 && $time->hour < 15) ? 'pagi' : 'sore';
    }

    public function getShiftLabelAttribute(): string
    {
        return $this->shift_type === 'sore'
            ? 'Shift Sore (15:00 - 21:00)'
            : 'Shift Pagi (08:00 - 15:00)';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
