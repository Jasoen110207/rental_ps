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

    public function startShift(Request $request)
    {
        $validated = $request->validate([
            'starting_cash' => 'required|numeric|min:0',
            'pin' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($validated['pin'], auth()->user()->pin)) {
            return back()->with('error', 'PIN yang Anda masukkan salah!');
        }

        Shift::where('status', 'active')->update(['status' => 'closed', 'end_time' => \Carbon\Carbon::now()]);
        Shift::create([
            'user_id' => auth()->id() ?? 1,
            'start_time' => \Carbon\Carbon::now(),
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
        $validated = $request->validate(['notes' => 'nullable|string']);
        
        $revenue = \App\Models\PlaySession::where('status', 'completed')
            ->where('end_time', '>=', $shift->start_time)->sum('total_amount');
        $txCount = \App\Models\PlaySession::where('status', 'completed')
            ->where('end_time', '>=', $shift->start_time)->count();
            
        $shift->update([
            'end_time' => \Carbon\Carbon::now(),
            'total_revenue' => $revenue,
            'transactions_count' => $txCount,
            'notes' => $validated['notes'] ?? $shift->notes,
            'status' => 'closed',
        ]);

        return back()->with('success', 'Shift berhasil ditutup dan diserahkan.');
    }

    /**
     * Riwayat shift kasir yang sedang login.
     */
    public function index(Request $request)
    {
        $shifts = Shift::orderBy('created_at', 'desc')->paginate(8);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'data' => $shifts,
            ]);
        }

        $activeShift = Shift::with('user')->where('status', 'active')->latest()->first();
        $ongoingRentalsCount = \App\Models\PlaySession::where('status', 'active')->count();
        $liveShiftRevenue = 0;
        $liveShiftTransactions = 0;
        if ($activeShift) {
            $liveShiftRevenue = \App\Models\PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)->sum('total_amount');
            $liveShiftTransactions = \App\Models\PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)->count();
        }
        $shiftHistory = $shifts;

        return view('admin.shifts.index', compact('shifts', 'activeShift', 'ongoingRentalsCount', 'liveShiftRevenue', 'liveShiftTransactions', 'shiftHistory'));
    }
}
