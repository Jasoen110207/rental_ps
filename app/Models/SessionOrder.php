<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionOrder extends Model
{
    /** @use HasFactory<\Database\Factories\SessionOrderFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke PlaySession (SessionOrder milik satu PlaySession)
     */
    public function playSession(): BelongsTo
    {
        return $this->belongsTo(PlaySession::class);
    }

    /**
     * Relasi ke Product (SessionOrder milik satu Product)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
