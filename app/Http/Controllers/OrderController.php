<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Models\Product;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request, $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $session = PlaySession::findOrFail($sessionId);
        $product = Product::findOrFail($validated['product_id']);

        try {
            $order = $this->orderService->addFoodToSession($session, $product, $validated['quantity']);

            return response()->json([
                'message' => 'Pesanan F&B berhasil ditambahkan ke sesi bermain.',
                'data' => $order,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
