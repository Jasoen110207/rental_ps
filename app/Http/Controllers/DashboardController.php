<?php

namespace App\Http\Controllers;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Shift;
use App\Models\Tv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tvs = Tv::with(['playSessions' => function ($query) {
            $query->where('status', 'active')->with('sessionOrders.product');
        }, 'customerRequests' => function ($query) {
            $query->where('status', 'pending');
        }])->orderBy('id')->get();

        $now = Carbon::now();

        // Calculate summary counters
        $totalUnits = $tvs->count();
        $availableUnits = $tvs->where('status', 'available')->count();
        $playingUnits = $tvs->where('status', 'playing')->count();
        
        $almostFinishedUnits = 0;
        $timeUpUnits = 0;

        foreach ($tvs as $tv) {
            $activeSession = $tv->playSessions->first();
            if ($activeSession && $activeSession->billing_type === 'prepaid' && $activeSession->end_time) {
                if ($activeSession->end_time->isPast()) {
                    $timeUpUnits++;
                } elseif ($activeSession->end_time->diffInMinutes($now) <= 10) {
                    $almostFinishedUnits++;
                }
            }
        }

        $pendingRequestsCount = CustomerRequest::where('status', 'pending')->count();
        $activeShift = Shift::where('status', 'active')->latest()->first();
        $products = Product::where('is_available', true)->get();

        return view('admin.dashboard', compact(
            'tvs',
            'totalUnits',
            'availableUnits',
            'playingUnits',
            'almostFinishedUnits',
            'timeUpUnits',
            'pendingRequestsCount',
            'activeShift',
            'products'
        ));
    }

    /**
     * Real-time API status polling for cashier dashboard
     */
    public function apiStatus()
    {
        $tvs = Tv::with(['playSessions' => function ($query) {
            $query->where('status', 'active')->with('sessionOrders.product');
        }, 'customerRequests' => function ($query) {
            $query->where('status', 'pending');
        }])->orderBy('id')->get();

        $now = Carbon::now();
        $tvData = [];

        $availableCount = 0;
        $playingCount = 0;
        $almostFinishedCount = 0;
        $timeUpCount = 0;

        foreach ($tvs as $tv) {
            $activeSession = $tv->playSessions->first();
            $sessionInfo = null;

            if ($activeSession) {
                $elapsedSeconds = $activeSession->start_time->diffInSeconds($now);
                $remainingSeconds = 0;
                $isAlmostFinished = false;
                $isTimeUp = false;

                $rentalCost = $activeSession->rental_amount;

                if ($activeSession->billing_type === 'prepaid' && $activeSession->end_time) {
                    if ($activeSession->end_time->isPast()) {
                        $remainingSeconds = 0;
                        $isTimeUp = true;
                        $timeUpCount++;
                    } else {
                        $remainingSeconds = $now->diffInSeconds($activeSession->end_time, false);
                        if ($remainingSeconds <= 600) { // <= 10 mins
                            $isAlmostFinished = true;
                            $almostFinishedCount++;
                        }
                    }
                } elseif ($activeSession->billing_type === 'postpaid') {
                    // Running cost calculation for postpaid (hourly rate * elapsed hours ceil)
                    $elapsedHours = max(1, ceil($elapsedSeconds / 3600));
                    $rentalCost = (int) ($elapsedHours * $tv->price_per_hour);
                }

                $fnbCost = $activeSession->sessionOrders->sum('subtotal');
                $totalCost = $rentalCost + $fnbCost;

                $sessionInfo = [
                    'id' => $activeSession->id,
                    'billing_type' => $activeSession->billing_type,
                    'start_time' => $activeSession->start_time->format('H:i'),
                    'end_time' => $activeSession->end_time ? $activeSession->end_time->format('H:i') : null,
                    'elapsed_seconds' => $elapsedSeconds,
                    'remaining_seconds' => max(0, $remainingSeconds),
                    'is_almost_finished' => $isAlmostFinished,
                    'is_time_up' => $isTimeUp,
                    'rental_cost' => $rentalCost,
                    'fnb_cost' => $fnbCost,
                    'total_cost' => $totalCost,
                    'orders' => $activeSession->sessionOrders->map(function ($order) {
                        return [
                            'name' => $order->product ? $order->product->name : 'Item',
                            'quantity' => $order->quantity,
                            'subtotal' => $order->subtotal,
                        ];
                    }),
                ];
            }

            if ($tv->status === 'available') {
                $availableCount++;
            } elseif ($tv->status === 'playing') {
                $playingCount++;
            }

            $tvData[] = [
                'id' => $tv->id,
                'name' => $tv->name,
                'type' => $tv->type,
                'price_per_hour' => $tv->price_per_hour,
                'status' => $tv->status,
                'is_buzzer_on' => (bool) $tv->is_buzzer_on,
                'pending_requests' => $tv->customerRequests->count(),
                'active_session' => $sessionInfo,
            ];
        }

        $pendingRequests = CustomerRequest::with('tv')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'tv_name' => $req->tv ? $req->tv->name : 'Meja',
                    'tv_id' => $req->tv_id,
                    'type' => $req->type,
                    'payload' => $req->payload,
                    'time_ago' => $req->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => $tvs->count(),
                'available' => $availableCount,
                'playing' => $playingCount,
                'almost_finished' => $almostFinishedCount,
                'time_up' => $timeUpCount,
                'pending_requests_count' => $pendingRequests->count(),
            ],
            'tvs' => $tvData,
            'pending_requests' => $pendingRequests,
            'server_time' => Carbon::now()->isoFormat('dddd, D MMMM YYYY • HH:mm:ss'),
        ]);
    }
}
