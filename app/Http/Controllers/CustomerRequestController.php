<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerRequestController extends Controller
{
    /**
     * Menyimpan permintaan pelanggan dari QR code di meja.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tv_id' => 'required|exists:tvs,id',
            'type' => 'required|in:add_time,order_food,service_call',
            'payload' => 'nullable|array',
        ]);

        $customerRequest = CustomerRequest::create([
            'tv_id' => $validated['tv_id'],
            'type' => $validated['type'],
            'payload' => $validated['payload'] ?? [],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Permintaan berhasil dikirim ke kasir.',
            'data' => $customerRequest->load('tv'),
        ], 201);
    }

    /**
     * Menampilkan daftar permintaan pelanggan berstatus pending untuk kasir.
     */
    public function index(): JsonResponse
    {
        $requests = CustomerRequest::with('tv')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $requests,
        ]);
    }

    /**
     * Memproses (approve/reject) permintaan pelanggan oleh kasir.
     */
    public function update(Request $request, CustomerRequest $customerRequest): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $customerRequest->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Status permintaan berhasil diperbarui.',
            'data' => $customerRequest->fresh('tv'),
        ]);
    }
}
