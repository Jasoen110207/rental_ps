<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaySession extends Model
{
    /** @use HasFactory<\Database\Factories\PlaySessionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke Tv (PlaySession milik satu Tv)
     */
    public function tv(): BelongsTo
    {
        return $this->belongsTo(Tv::class);
    }

    /**
     * Relasi ke User (PlaySession milik satu User / Kasir)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke SessionOrder (PlaySession memiliki banyak SessionOrder)
     */
    public function sessionOrders(): HasMany
    {
        return $this->hasMany(SessionOrder::class);
    }
}
