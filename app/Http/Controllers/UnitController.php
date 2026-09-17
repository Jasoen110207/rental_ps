<?php

namespace App\Http\Controllers;

use App\Models\Tv;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Tv::with(['playSessions' => function ($q) {
            $q->where('status', 'active');
        }])->latest()->get();

        return view('admin.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:ps3,ps4,ps5,sim_racing,nintendo_switch',
            'price_per_hour' => 'required|numeric|min:1000',
            'status' => 'required|in:available,maintenance',
        ]);

        $validated['is_buzzer_on'] = false;

        Tv::create($validated);

        return back()->with('success', 'Unit konsol berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $tv = Tv::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:ps3,ps4,ps5,sim_racing,nintendo_switch',
            'price_per_hour' => 'required|numeric|min:1000',
            'status' => 'required|in:available,playing,maintenance',
        ]);

        $tv->update($validated);

        return back()->with('success', 'Unit ' . $tv->name . ' berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $tv = Tv::findOrFail($id);
        $newStatus = $tv->status === 'maintenance' ? 'available' : 'maintenance';
        $tv->update(['status' => $newStatus]);

        return back()->with('success', 'Status unit ' . $tv->name . ' diubah menjadi ' . $newStatus);
    }

    public function qr($id)
    {
        $tv = Tv::findOrFail($id);
        $customerUrl = route('customer.index', ['tv_id' => $tv->id]);

        return view('admin.units.qr', compact('tv', 'customerUrl'));
    }

    public function destroy($id)
    {
        $tv = Tv::findOrFail($id);

        if ($tv->playSessions()->exists()) {
            return back()->with('error', 'Gagal dihapus: Unit ini memiliki riwayat sesi bermain/transaksi. Ubah statusnya menjadi Maintenance jika tidak digunakan.');
        }

        $tv->delete();

        return back()->with('success', 'Unit ' . $tv->name . ' berhasil dihapus.');
    }
}
