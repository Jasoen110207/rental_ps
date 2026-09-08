<?php

namespace App\Services;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Tv;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomerRequestService
{
    /**
     * Membuat permintaan pelanggan baru (endpoint publik via QR Code).
     *
     * @param  string  $type  ('add_time', 'order_food', 'service_call')
     *
     * @throws Exception
     */
    public function createRequest(Tv $tv, string $type, array $payload): CustomerRequest
    {
        $hasActiveSession = PlaySession::where('tv_id', $tv->id)
            ->where('status', 'active')
            ->exists();

        if (! $hasActiveSession) {
            throw new Exception('TV ini tidak sedang digunakan.');
        }

        return CustomerRequest::create([
            'tv_id' => $tv->id,
            'type' => $type,
            'payload' => $payload,
            'status' => 'pending',
        ]);
    }

    /**
     * Menyetujui permintaan pelanggan.
     *
     * @throws Exception
     */
    public function approveRequest(CustomerRequest $customerRequest): CustomerRequest
    {
        if ($customerRequest->status !== 'pending') {
            throw new Exception('Request ini sudah diproses sebelumnya.');
        }

        if ($customerRequest->type === 'order_food') {
            DB::transaction(function () use ($customerRequest) {
                $productId = $customerRequest->payload['product_id'] ?? null;
                $quantity = $customerRequest->payload['quantity'] ?? null;

                if (! $productId || ! $quantity) {
                    throw new Exception('Payload pesanan F&B tidak lengkap.');
                }

                $session = PlaySession::where('tv_id', $customerRequest->tv_id)
                    ->where('status', 'active')
                    ->first();

                if (! $session) {
                    throw new Exception('TV ini tidak memiliki sesi bermain aktif.');
                }

                $product = Product::find($productId);

                if (! $product) {
                    throw new Exception('Produk tidak ditemukan.');
                }

                (new OrderService)->addFoodToSession($session, $product, (int) $quantity);
            });
        }

        $customerRequest->update([
            'status' => 'approved',
            'payload' => $customerRequest->payload,
        ]);

        return $customerRequest->fresh('tv');
    }

    /**
     * Menolak permintaan pelanggan.
     *
     * @throws Exception
     */
    public function rejectRequest(CustomerRequest $customerRequest): CustomerRequest
    {
        if ($customerRequest->status !== 'pending') {
            throw new Exception('Request ini sudah diproses sebelumnya.');
        }

        $customerRequest->update([
            'status' => 'rejected',
        ]);

        return $customerRequest->fresh('tv');
    }
}
