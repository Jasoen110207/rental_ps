<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_datetime' => 'datetime',
        'duration_hours' => 'integer',
        'total_amount' => 'integer',
    ];

    public function tv(): BelongsTo
    {
        return $this->belongsTo(Tv::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
