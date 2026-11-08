<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function __construct(public MidtransService $midtransService) {}

    /**
     * Endpoint AJAX untuk menghasilkan Snap Token saat kasir memilih metode QRIS.
     */
    public function createSnapToken(Request $request, int $sessionId): JsonResponse
    {
        $session = PlaySession::with(['tv', 'sessionOrders.product'])->findOrFail($sessionId);

        if ($session->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi rental ini sudah selesai dibayar.',
            ], 400);
        }

        $customRentalAmount = $request->filled('custom_rental_amount')
            ? (int) $request->input('custom_rental_amount')
            : null;

        try {
            $result = $this->midtransService->createSnapTransaction($session, $customRentalAmount);

            return response()->json([
                'success' => true,
                'snap_token' => $result['snap_token'],
                'order_id' => $result['order_id'],
                'rental_amount' => $result['rental_amount'],
                'fnb_amount' => $result['fnb_amount'],
                'grand_total' => $result['grand_total'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi Midtrans: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint Webhook Notification dari Midtrans.
     */
    public function webhook(Request $request): JsonResponse
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');
        $transactionStatus = (string) $request->input('transaction_status');
        $fraudStatus = (string) $request->input('fraud_status');

        $serverKey = (string) config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        if (! hash_equals($expectedSignature, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $session = PlaySession::where('midtrans_order_id', $orderId)->first();
        if (! $session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $isPaid = ($transactionStatus === 'capture' && $fraudStatus === 'accept')
            || $transactionStatus === 'settlement';

        if ($isPaid) {
            $this->midtransService->finalizeSessionCheckout(
                $session,
                'qris',
                'Dibayar otomatis via Midtrans QRIS ('.$orderId.')'
            );
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
