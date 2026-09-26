<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function exportCsv(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $period = $request->query('period', 'all');
        $query = PlaySession::with(['tv', 'user'])->where('status', 'completed');

        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month);
        }

        $data = $query->get();

        $filename = 'laporan_'.$period.'_'.now()->format('Ymd').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'TV', 'Kasir', 'Mulai', 'Selesai', 'Rental', 'F&B', 'Total', 'Metode Bayar']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->tv->name ?? '-',
                    $row->user->name ?? '-',
                    $row->start_time,
                    $row->end_time,
                    $row->rental_amount,
                    $row->fnb_amount,
                    $row->total_amount,
                    $row->payment_method,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request): \Illuminate\Http\Response
    {
        $period = $request->query('period', 'all');
        $query = PlaySession::with(['tv', 'user'])->where('status', 'completed');

        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('data', 'period'));

        return $pdf->download('laporan_'.$period.'_'.now()->format('Ymd').'.pdf');
    }
}

