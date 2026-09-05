<?php

namespace App\Services;

use App\Models\PlaySession;
use App\Models\Product;
use App\Models\SessionOrder;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Menambahkan pesanan makanan/minuman ke dalam sesi bermain yang sedang aktif.
     *
     * @param PlaySession $session
     * @param Product $product
     * @param int $quantity
     * @return SessionOrder
     * @throws Exception
     */
    public function addFoodToSession(PlaySession $session, Product $product, int $quantity): SessionOrder
    {
        if ($session->status !== 'active') {
            throw new Exception("Tidak dapat menambah pesanan ke sesi yang sudah selesai.");
        }

        if ($product->stock < $quantity) {
            throw new Exception("Stok produk '{$product->name}' tidak cukup. Stok tersedia: {$product->stock}.");
        }

        return DB::transaction(function () use ($session, $product, $quantity) {
            // Kurangi stok produk
            $product->decrement('stock', $quantity);

            $subtotal = $product->price * $quantity;

            // Tambahkan record pesanan baru di tabel session_orders
            return SessionOrder::create([
                'play_session_id' => $session->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);
        });
    }
}
