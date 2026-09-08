<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerRequestResource;
use App\Models\CustomerRequest;
use App\Models\Tv;
use App\Services\CustomerRequestService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerRequestController extends Controller
{
    public function __construct(
        protected CustomerRequestService $customerRequestService
    ) {}

    /**
     * Menyimpan permintaan pelanggan dari QR code di meja (Publik Endpoint).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tv_id' => 'required|integer|exists:tvs,id',
            'type' => 'required|in:add_time,order_food,service_call',
            'payload' => 'required|array',
            'payload.product_id' => 'required_if:type,order_food|nullable|integer|exists:products,id',
            'payload.quantity' => 'required_if:type,order_food|nullable|integer|min:1',
            'payload.duration_minutes' => 'required_if:type,add_time|nullable|integer|min:30',
        ]);

        $tv = Tv::findOrFail($validated['tv_id']);

        try {
            $customerRequest = $this->customerRequestService->createRequest(
                $tv,
                $validated['type'],
                $validated['payload']
            );

            return response()->json([
                'message' => 'Request berhasil dikirim. Mohon tunggu konfirmasi kasir.',
                'data' => new CustomerRequestResource($customerRequest->load('tv')),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Menampilkan daftar permintaan pelanggan berstatus pending untuk kasir (Protected Endpoint).
     */
    public function index(): JsonResponse
    {
        $requests = CustomerRequest::with('tv')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => CustomerRequestResource::collection($requests),
        ]);
    }

    /**
     * Menyetujui permintaan pelanggan (Protected Endpoint).
     */
    public function approve(int $id): JsonResponse
    {
        $customerRequest = CustomerRequest::findOrFail($id);

        try {
            $approved = $this->customerRequestService->approveRequest($customerRequest);

            return response()->json([
                'message' => 'Request berhasil di-approve.',
                'data' => new CustomerRequestResource($approved),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Menolak permintaan pelanggan (Protected Endpoint).
     */
    public function reject(int $id): JsonResponse
    {
        $customerRequest = CustomerRequest::findOrFail($id);

        try {
            $rejected = $this->customerRequestService->rejectRequest($customerRequest);

            return response()->json([
                'message' => 'Request berhasil di-reject.',
                'data' => new CustomerRequestResource($rejected),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update status request opsional/legacy.
     */
    public function update(Request $request, CustomerRequest $customerRequest): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($validated['status'] === 'approved') {
            return $this->approve($customerRequest->id);
        }

        return $this->reject($customerRequest->id);
    }
}
