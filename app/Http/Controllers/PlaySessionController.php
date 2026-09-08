<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaySessionResource;
use App\Models\PlaySession;
use App\Models\Tv;
use App\Services\OrderService;
use App\Services\PlaySessionService;
use Carbon\Carbon;
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
            'duration_minutes' => 'required_if:billing_type,prepaid|nullable|integer|min:30',
        ]);

        $tv = Tv::findOrFail($validated['tv_id']);
        $kasir = $request->user();

        try {
            $duration = isset($validated['duration_minutes']) ? (int) $validated['duration_minutes'] : null;
            $session = $this->playSessionService->startSession($tv, $kasir, $validated['billing_type'], $duration);

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

    /**
     * Detail sesi bermain beserta status sisa waktu.
     */
    public function show(int $id): JsonResponse
    {
        $session = PlaySession::with(['tv', 'user', 'sessionOrders.product'])->findOrFail($id);

        $extra = [];

        if ($session->billing_type === 'prepaid' && $session->status === 'active') {
            $duration = $session->duration_minutes ?? 0;
            $endTime = $session->end_time ?? Carbon::parse($session->start_time)->addMinutes($duration);
            $remainingMinutes = max(0, (int) Carbon::now()->diffInMinutes($endTime, false));

            $extra['remaining_minutes'] = $remainingMinutes;
            $extra['is_expired'] = Carbon::now()->greaterThanOrEqualTo($endTime);
        }

        return response()->json([
            'data' => new PlaySessionResource($session),
            'time_info' => $extra,
        ]);
    }
}
