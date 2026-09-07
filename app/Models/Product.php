<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke SessionOrder (Product memiliki banyak SessionOrder)
     */
    public function sessionOrders(): HasMany
    {
        return $this->hasMany(SessionOrder::class);
    }
}
