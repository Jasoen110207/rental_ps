<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PlaySession;
use App\Models\Shift;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $lastClosedShift = Shift::where('status', 'closed')->latest('end_time')->first();
        $suggestedStartingCash = $lastClosedShift ? $lastClosedShift->leftover_cash : 200000;
        $defaultShiftType = Shift::detectShiftType();

        return view('admin.shifts.index', compact(
            'shifts',
            'activeShift',
            'ongoingRentalsCount',
            'liveShiftRevenue',
            'liveShiftTransactions',
            'shiftHistory',
            'lastClosedShift',
            'suggestedStartingCash',
            'defaultShiftType'
        ));
    }

    /**
     * Memulai shift kasir via form web (route admin.shifts.start).
     */
    public function startShift(Request $request)
    {
        $validated = $request->validate([
            'shift_type' => 'required|in:pagi,sore',
            'starting_cash' => 'required|numeric|min:0',
            'pin' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $userPin = (string) (auth()->user()->pin ?? '');
        $pinValid = ($validated['pin'] === $userPin) || Hash::check($validated['pin'], $userPin);
        if (! $pinValid) {
            return back()->with('error', 'PIN yang Anda masukkan salah!');
        }

        // Tutup shift aktif yang menggantung agar tidak ganda
        Shift::where('status', 'active')->update([
            'status' => 'closed',
            'end_time' => Carbon::now(),
        ]);

        $newShift = Shift::create([
            'user_id' => Auth::id() ?? 1,
            'shift_type' => $validated['shift_type'],
            'start_time' => Carbon::now(),
            'starting_cash' => (int) $validated['starting_cash'],
            'total_revenue' => 0,
            'reported_revenue' => 0,
            'leftover_cash' => 0,
            'transactions_count' => 0,
            'notes' => $validated['notes'] ?? 'Shift baru dibuka.',
            'status' => 'active',
        ]);

        if ($newShift->user) {
            Payroll::syncForUser($newShift->user);
        }

        return back()->with('success', 'Shift baru ('.($validated['shift_type'] === 'pagi' ? 'Pagi' : 'Sore').') berhasil dimulai.');
    }

    /**
     * Menutup shift kasir via form web (route admin.shifts.end).
     */
    public function endShift(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'reported_revenue' => 'required|numeric|min:0',
            'leftover_cash' => 'required|numeric|min:0',
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
            'reported_revenue' => (int) $validated['reported_revenue'],
            'leftover_cash' => (int) $validated['leftover_cash'],
            'transactions_count' => $txCount,
            'notes' => $validated['notes'] ?? $shift->notes,
            'status' => 'closed',
        ]);

        if ($shift->user) {
            Payroll::syncForUser($shift->user);
        }

        return back()->with('success', 'Shift kasir berhasil ditutup dan diserahkan.');
    }
}
