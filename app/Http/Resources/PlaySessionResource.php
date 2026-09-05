<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PlaySessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $startTime = $this->start_time ? Carbon::parse($this->start_time) : null;
        $endTime = $this->end_time ? Carbon::parse($this->end_time) : null;

        return [
            'id' => $this->id,
            'tv_id' => $this->tv_id,
            'user_id' => $this->user_id,
            'kasir_name' => $this->whenLoaded('user', fn() => $this->user?->name, $this->user?->name),
            'billing_type' => $this->billing_type,
            'start_time' => $startTime?->toIso8601String(),
            'start_time_formatted' => $startTime?->format('H:i'),
            'end_time' => $endTime?->toIso8601String(),
            'end_time_formatted' => $endTime?->format('H:i'),
            'status' => $this->status,
            'total_amount' => (int) $this->total_amount,
            'orders' => $this->whenLoaded('sessionOrders', function () {
                return $this->sessionOrders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'product_id' => $order->product_id,
                        'product_name' => $order->product?->name,
                        'quantity' => (int) $order->quantity,
                        'subtotal' => (int) $order->subtotal,
                    ];
                });
            }, function () {
                return $this->sessionOrders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'product_id' => $order->product_id,
                        'product_name' => $order->product?->name,
                        'quantity' => (int) $order->quantity,
                        'subtotal' => (int) $order->subtotal,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
