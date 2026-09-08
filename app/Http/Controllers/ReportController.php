<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Menampilkan laporan agregasi pendapatan untuk Admin.
     */
    public function index(Request $request): JsonResponse
    {
        $period = $request->query('period', 'today'); // default 'today', options: 'today', 'month', 'all'

        $query = PlaySession::where('status', 'completed');

        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'month') {
            $query->whereYear('created_at', Carbon::now()->year)
                  ->whereMonth('created_at', Carbon::now()->month);
        }

        $totalTransactions = (clone $query)->count();
        $totalRevenue = (int) (clone $query)->sum('total_amount');
        $totalRentalRevenue = (int) (clone $query)->sum('rental_amount');
        $totalFnbRevenue = (int) (clone $query)->sum('fnb_amount');

        return response()->json([
            'period' => $period,
            'summary' => [
                'total_transactions' => $totalTransactions,
                'total_revenue' => $totalRevenue,
                'total_rental_revenue' => $totalRentalRevenue,
                'total_fnb_revenue' => $totalFnbRevenue,
            ],
            'data' => (clone $query)->with(['tv', 'user'])->latest()->get(),
        ]);
    }
}
