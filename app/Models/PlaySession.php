<?php

namespace App\Models;

use Database\Factories\PlaySessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PlaySession extends Model
{
    /** @use HasFactory<PlaySessionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::creating(function (PlaySession $session) {
            if (empty($session->session_token)) {
                $session->session_token = Str::random(40);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

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
