<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index()
    {
        $activeShift = Shift::with('user')
            ->where('status', 'active')
            ->latest()
            ->first();

        // Count ongoing active rentals
        $ongoingRentalsCount = PlaySession::where('status', 'active')->count();

        // Calculate live sales during active shift if active
        $liveShiftRevenue = 0;
        $liveShiftTransactions = 0;
        if ($activeShift) {
            $liveShiftRevenue = PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)
                ->sum('total_amount');
            $liveShiftTransactions = PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)
                ->count();
        }

        $shiftHistory = Shift::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.shifts.index', compact(
            'activeShift',
            'ongoingRentalsCount',
            'liveShiftRevenue',
            'liveShiftTransactions',
            'shiftHistory'
        ));
    }

    public function startShift(Request $request)
    {
        $validated = $request->validate([
            'starting_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Close any lingering active shift
        Shift::where('status', 'active')->update([
            'status' => 'closed',
            'end_time' => Carbon::now(),
        ]);

        Shift::create([
            'user_id' => Auth::id() ?? 1,
            'start_time' => Carbon::now(),
            'starting_cash' => $validated['starting_cash'],
            'total_revenue' => 0,
            'transactions_count' => 0,
            'notes' => $validated['notes'] ?? 'Shift baru dibuka.',
            'status' => 'active',
        ]);

        return back()->with('success', 'Shift baru berhasil dimulai.');
    }

    public function endShift(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $revenue = PlaySession::where('status', 'completed')
            ->where('end_time', '>=', $shift->start_time)
            ->sum('total_amount');

        $txCount = PlaySession::where('status', 'completed')
            ->where('end_time', '>=', $shift->start_time)
            ->count();

        $shift->update([
            'end_time' => Carbon::now(),
            'total_revenue' => $revenue,
            'transactions_count' => $txCount,
            'notes' => $validated['notes'] ?? $shift->notes,
            'status' => 'closed',
        ]);

        return back()->with('success', 'Shift kasir berhasil ditutup dan diserahkan.');
    }
}
