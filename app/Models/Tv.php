<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tv extends Model
{
    /** @use HasFactory<\Database\Factories\TvFactory> */
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
}
