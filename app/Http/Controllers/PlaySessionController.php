<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaySessionResource;
use App\Models\PlaySession;
use App\Models\Tv;
use App\Services\OrderService;
use App\Services\PlaySessionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaySessionController extends Controller
{
    public function __construct(
        protected PlaySessionService $playSessionService,
        protected OrderService $orderService
    ) {}

    /**
     * Memulai sesi bermain baru (Start Playing).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tv_id' => 'required|exists:tvs,id',
            'billing_type' => 'required|in:prepaid,postpaid',
        ]);

        $tv = Tv::findOrFail($validated['tv_id']);
        $kasir = $request->user() ?? \App\Models\User::where('role', 'kasir')->first() ?? \App\Models\User::first();

        try {
            $session = $this->playSessionService->startSession($tv, $kasir, $validated['billing_type']);

            return response()->json([
                'message' => 'Sesi bermain berhasil dimulai.',
                'data' => new PlaySessionResource($session->load(['tv', 'user'])),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Mengakhiri sesi bermain (Stop Playing & Hitung Total Tagihan).
     */
    public function update(Request $request, PlaySession $playSession): JsonResponse
    {
        try {
            $completedSession = $this->playSessionService->endSession($playSession);

            return response()->json([
                'message' => 'Sesi bermain berhasil dihentikan.',
                'data' => new PlaySessionResource($completedSession),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
