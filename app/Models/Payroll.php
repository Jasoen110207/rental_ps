<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

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
}
