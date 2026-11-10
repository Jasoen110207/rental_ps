<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PlaySession;
use App\Models\Tv;
use Carbon\Carbon;
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

    public function checkIn(Booking $booking)
    {
        if ($booking->status !== 'Pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        $tv = Tv::findOrFail($booking->tv_id);

        if ($tv->status !== 'available') {
            return back()->with('error', 'Unit '.$tv->name.' sedang digunakan atau maintenance! Selesaikan sesi sebelumnya terlebih dahulu.');
        }

        $startTime = Carbon::now();
        $hours = max(1, (int) $booking->duration_hours);
        $endTime = $startTime->copy()->addMinutes($hours * 60);
        $rentalAmount = $booking->total_amount > 0
            ? (int) $booking->total_amount
            : (int) round($hours * $tv->price_per_hour);

        DB::transaction(function () use ($booking, $tv, $startTime, $endTime, $rentalAmount) {
            PlaySession::create([
                'tv_id' => $tv->id,
                'user_id' => Auth::id() ?? 1,
                'customer_name' => $booking->customer_name,
                'controller_count' => 2,
                'billing_type' => 'prepaid',
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'active',
                'rental_amount' => $rentalAmount,
                'fnb_amount' => 0,
                'total_amount' => $rentalAmount,
                'payment_method' => 'cash',
                'notes' => 'Check-in Booking BK-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT),
            ]);

            $booking->update(['status' => 'Active']);

            $tv->update([
                'status' => 'playing',
                'is_buzzer_on' => false,
            ]);
        });

        return back()->with('success', 'Check-in berhasil! Billing untuk '.$tv->name.' ('.$booking->customer_name.') telah dimulai.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status !== 'Pending') {
            return back()->with('error', 'Hanya booking berstatus Pending yang dapat dibatalkan.');
        }

        $booking->delete();

        return back()->with('success', 'Booking berhasil dibatalkan dan jadwal unit telah dikosongkan.');
    }
}
