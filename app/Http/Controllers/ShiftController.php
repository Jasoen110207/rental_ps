<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\ShiftService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function __construct(
        protected ShiftService $shiftService
    ) {}

    /**
     * Memulai shift kasir.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $shift = $this->shiftService->startShift($request->user());

            return response()->json([
                'message' => 'Shift berhasil dimulai.',
                'data' => $shift->load('user'),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Mengakhiri shift kasir.
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $shift = $this->shiftService->endShift($request->user());

            return response()->json([
                'message' => 'Shift berhasil diakhiri.',
                'data' => $shift,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Riwayat shift kasir yang sedang login.
     */
    public function index(Request $request): JsonResponse
    {
        $shifts = Shift::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $shifts,
        ]);
    }
}
