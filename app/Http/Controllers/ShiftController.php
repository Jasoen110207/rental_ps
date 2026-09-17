<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Models\Shift;
use App\Services\ShiftService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function __construct(
        protected ShiftService $shiftService
    ) {}

    /**
     * Halaman web manajemen shift (butuh $activeShift dkk. untuk blade)
     * sekaligus endpoint JSON riwayat shift milik kasir login (API).
     */
    public function index(Request $request)
    {
        if ($request->expectsJson() || $request->wantsJson()) {
            $shifts = Shift::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'data' => $shifts,
            ]);
        }

        $shifts = Shift::orderBy('created_at', 'desc')->paginate(8);
        $activeShift = Shift::with('user')->where('status', 'active')->latest()->first();
        $ongoingRentalsCount = PlaySession::where('status', 'active')->count();
        
        $liveShiftRevenue = 0;
        $liveShiftTransactions = 0;
        if ($activeShift) {
            $liveShiftRevenue = PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)->sum('total_amount');
            $liveShiftTransactions = PlaySession::where('status', 'completed')
                ->where('end_time', '>=', $activeShift->start_time)->count();
        }

        $shiftHistory = Shift::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.shifts.index', compact(
            'shifts',
            'activeShift',
            'ongoingRentalsCount',
            'liveShiftRevenue',
            'liveShiftTransactions',
            'shiftHistory'
        ));
    }

    /**
     * Memulai shift kasir via form web (route admin.shifts.start).
     */
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
        // Tutup shift aktif yang menggantung agar tidak ganda
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

    /**
     * Menutup shift kasir via form web (route admin.shifts.end).
     */
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
