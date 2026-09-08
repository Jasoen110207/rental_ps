<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Models\Setting;
use App\Models\Tv;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = PlaySession::with(['tv', 'user', 'sessionOrders.product'])
            ->where('status', 'completed')
            ->orderBy('end_time', 'desc');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('tv', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tv_id')) {
            $query->where('tv_id', $request->get('tv_id'));
        }

        if ($request->filled('billing_type')) {
            $query->where('billing_type', $request->get('billing_type'));
        }

        if ($request->filled('date')) {
            $query->whereDate('end_time', $request->get('date'));
        }

        $transactions = $query->paginate(15)->withQueryString();

        $tvs = Tv::orderBy('name')->get();
        $totalRevenue = PlaySession::where('status', 'completed')->sum('total_amount');
        $totalRentalRevenue = PlaySession::where('status', 'completed')->sum('rental_amount');
        $totalFnbRevenue = PlaySession::where('status', 'completed')->sum('fnb_amount');

        return view('admin.transactions.index', compact(
            'transactions',
            'tvs',
            'totalRevenue',
            'totalRentalRevenue',
            'totalFnbRevenue'
        ));
    }

    public function show($id)
    {
        $session = PlaySession::with(['tv', 'user', 'sessionOrders.product'])->findOrFail($id);

        return view('admin.transactions.show', compact('session'));
    }

    public function printInvoice($id)
    {
        $session = PlaySession::with(['tv', 'user', 'sessionOrders.product'])->findOrFail($id);
        $storeName = Setting::get('store_name', 'TambahBang Rental PS');
        $storeAddress = Setting::get('store_address', 'Jl. Game Arena No. 42');
        $storePhone = Setting::get('store_phone', '0812-3456-7890');

        return view('admin.transactions.invoice', compact('session', 'storeName', 'storeAddress', 'storePhone'));
    }
}
