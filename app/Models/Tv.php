<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Database\Factories\TvFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tv extends Model
{
    /** @use HasFactory<TvFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_buzzer_on' => 'boolean',
        ];
    }

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
     * Cek apakah unit sedang dimainkan pada rentang waktu booking yang diminta.
     */
    public function isInUse(CarbonInterface|string|null $startDatetime = null, int $durationHours = 1): bool
    {
        $bookingStart = $startDatetime ? Carbon::parse($startDatetime) : Carbon::now();
        $bookingEnd = $bookingStart->copy()->addHours(max(1, $durationHours));
        $now = Carbon::now();

        $activeSessions = $this->playSessions()->where('status', 'active')->get();

        if ($activeSessions->isNotEmpty()) {
            foreach ($activeSessions as $session) {
                $sessionStart = $session->start_time ?? $now;
                // Jika prepaid dan punya end_time, gunakan end_time (atau now jika lewat).
                // Jika postpaid (end_time null), sesi sedang berjalan saat ini sampai minimal waktu sekarang ($now).
                $sessionEnd = $session->end_time
                    ? ($session->end_time->gt($now) ? $session->end_time : $now)
                    : $now;

                if ($bookingStart->lte($sessionEnd) && $bookingEnd->gt($sessionStart)) {
                    return true;
                }
            }

            return false;
        }

        // Jika status TV 'playing' tanpa record play_sessions aktif, berarti sedang digunakan saat ini ($now)
        if ($this->status === 'playing') {
            return $bookingStart->lte($now) && $bookingEnd->gte($now);
        }

        return false;
    }

    /**
     * Cek apakah unit sudah memiliki booking (Pending / Active) yang bentrok dengan rentang waktu yang diminta.
     */
    public function hasOpenBooking(CarbonInterface|string|null $startDatetime = null, int $durationHours = 1): bool
    {
        if ($startDatetime === null) {
            return $this->bookings()->whereIn('status', ['Pending', 'Active'])->exists();
        }

        $bookingStart = Carbon::parse($startDatetime);
        $bookingEnd = $bookingStart->copy()->addHours(max(1, $durationHours));

        $openBookings = $this->bookings()
            ->whereIn('status', ['Pending', 'Active'])
            ->get(['start_datetime', 'duration_hours']);

        foreach ($openBookings as $existing) {
            $existingStart = Carbon::parse($existing->start_datetime);
            $existingEnd = $existingStart->copy()->addHours(max(1, (int) $existing->duration_hours));

            if ($bookingStart->lt($existingEnd) && $bookingEnd->gt($existingStart)) {
                return true;
            }
        }

        return false;
    }
}
