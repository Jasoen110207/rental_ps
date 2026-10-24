<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $tvs = Tv::with([
            'bookings' => fn ($q) => $q->whereIn('status', ['Pending', 'Active']),
            'playSessions' => fn ($q) => $q->where('status', 'active'),
        ])->orderBy('name')->get();
        $bookings = Booking::with('tv')->latest()->get();

        return view('booking.index', compact('tvs', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'tv_id' => 'required|exists:tvs,id',
            'start_datetime' => 'required|date',
            'duration_hours' => 'required|integer|min:1|max:24',
        ]);

        $tv = Tv::findOrFail($validated['tv_id']);
        $startDatetime = $validated['start_datetime'];
        $durationHours = (int) $validated['duration_hours'];

        if ($tv->isInUse($startDatetime, $durationHours)) {
            return back()
                ->withInput()
                ->with('error', 'Peringatan: Unit '.$tv->name.' sedang digunakan (dimainkan) pada waktu tersebut dan tidak dapat dibooking!');
        }

        if ($tv->hasOpenBooking($startDatetime, $durationHours)) {
            return back()
                ->withInput()
                ->with('error', 'Peringatan: Unit '.$tv->name.' sudah dibooking pada waktu tersebut!');
        }

        $estimatedAmount = (int) round($validated['duration_hours'] * $tv->price_per_hour);

        DB::transaction(function () use ($validated, $tv, $estimatedAmount) {
            Booking::create([
                'tv_id' => $tv->id,
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'start_datetime' => $validated['start_datetime'],
                'duration_hours' => $validated['duration_hours'],
                'status' => 'Pending',
                'total_amount' => $estimatedAmount,
            ]);
        });

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat untuk unit '.$tv->name.'.');
    }
}
