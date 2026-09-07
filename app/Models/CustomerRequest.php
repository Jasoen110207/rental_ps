<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRequest extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerRequestFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get attributes casting rules.
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * Relasi ke Tv (CustomerRequest milik satu Tv)
     */
    public function tv(): BelongsTo
    {
        return $this->belongsTo(Tv::class);
    }
}
