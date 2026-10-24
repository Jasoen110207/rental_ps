<?php

namespace App\Models;

use Database\Factories\TvFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tv extends Model
{
    /** @use HasFactory<TvFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke PlaySession (Tv memiliki banyak PlaySession)
     */
    public function playSessions(): HasMany
    {
        return $this->hasMany(PlaySession::class);
    }

    /**
     * Relasi ke CustomerRequest (Tv memiliki banyak CustomerRequest)
     */
    public function customerRequests(): HasMany
    {
        return $this->hasMany(CustomerRequest::class);
    }

    /**
     * Relasi ke Booking (Tv memiliki banyak Booking)
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Cek apakah unit sedang dimainkan (status playing atau punya sesi aktif).
     */
    public function isInUse(): bool
    {
        return $this->status === 'playing'
            || $this->playSessions()->where('status', 'active')->exists();
    }

    /**
     * Cek apakah unit sudah memiliki booking yang masih berjalan (Pending / Active).
     */
    public function hasOpenBooking(): bool
    {
        return $this->bookings()->whereIn('status', ['Pending', 'Active'])->exists();
    }
}
