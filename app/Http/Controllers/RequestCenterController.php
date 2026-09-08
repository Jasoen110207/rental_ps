<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\SessionOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestCenterController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('tab', 'all');

        $query = CustomerRequest::with(['tv.playSessions' => function ($q) {
            $q->where('status', 'active');
        }])->orderBy('created_at', 'desc');

        if ($filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filter === 'approved') {
            $query->where('status', 'approved');
        } elseif ($filter === 'rejected') {
            $query->where('status', 'rejected');
        }

        $requests = $query->paginate(15);

        $counts = [
            'all' => CustomerRequest::count(),
            'pending' => CustomerRequest::where('status', 'pending')->count(),
            'approved' => CustomerRequest::where('status', 'approved')->count(),
            'rejected' => CustomerRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.requests.index', compact('requests', 'filter', 'counts'));
    }

    public function approve(Request $request, $id)
    {
        $customerRequest = CustomerRequest::with('tv.playSessions')->findOrFail($id);

        if ($customerRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya!');
        }

        $activeSession = PlaySession::where('tv_id', $customerRequest->tv_id)
            ->where('status', 'active')
            ->first();

        DB::transaction(function () use ($customerRequest, $activeSession) {
            $payload = $customerRequest->payload;

            if ($customerRequest->type === 'add_time') {
                if ($activeSession) {
                    $hours = (float) ($payload['duration_hours'] ?? 1);
                    $addedMinutes = (int) round($hours * 60);
                    $addedFee = (int) round($hours * $activeSession->tv->price_per_hour);

                    $baseEndTime = $activeSession->end_time && $activeSession->end_time->isFuture()
                        ? $activeSession->end_time
                        : Carbon::now();

                    $newEndTime = $baseEndTime->copy()->addMinutes($addedMinutes);
                    $newRentalAmount = $activeSession->rental_amount + $addedFee;

                    $activeSession->update([
                        'billing_type' => 'prepaid',
                        'end_time' => $newEndTime,
                        'rental_amount' => $newRentalAmount,
                        'total_amount' => $newRentalAmount + $activeSession->fnb_amount,
                    ]);

                    $activeSession->tv->update(['is_buzzer_on' => false]);
                }
            } elseif ($customerRequest->type === 'order_food') {
                if ($activeSession && !empty($payload['items'])) {
                    $addedFnb = 0;
                    foreach ($payload['items'] as $item) {
                        $product = Product::find($item['product_id']);
                        if ($product) {
                            $qty = (int) ($item['quantity'] ?? 1);
                            $subtotal = $product->price * $qty;
                            $addedFnb += $subtotal;

                            if ($product->stock >= $qty) {
                                $product->decrement('stock', $qty);
                            }

                            SessionOrder::create([
                                'play_session_id' => $activeSession->id,
                                'product_id' => $product->id,
                                'quantity' => $qty,
                                'subtotal' => $subtotal,
                            ]);
                        }
                    }

                    $newFnbAmount = $activeSession->fnb_amount + $addedFnb;
                    $activeSession->update([
                        'fnb_amount' => $newFnbAmount,
                        'total_amount' => $activeSession->rental_amount + $newFnbAmount,
                    ]);
                }
            }

            $customerRequest->update(['status' => 'approved']);
        });

        return back()->with('success', 'Permintaan dari ' . ($customerRequest->tv->name ?? 'Meja') . ' berhasil disetujui!');
    }

    public function reject(Request $request, $id)
    {
        $customerRequest = CustomerRequest::findOrFail($id);

        if ($customerRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya!');
        }

        $customerRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan telah ditolak.');
    }
}
