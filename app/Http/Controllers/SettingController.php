<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Tv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        $rateToUnitTypeMap = [
            'rate_ps3' => 'ps3',
            'rate_ps4' => 'ps4',
            'rate_ps5' => 'ps5',
            'rate_sim_racing' => 'sim_racing',
            'rate_nintendo_switch' => 'nintendo_switch',
        ];

        DB::transaction(function () use ($data, $rateToUnitTypeMap) {
            foreach ($data as $key => $value) {
                $group = array_key_exists($key, $rateToUnitTypeMap) ? 'pricing' : 'general';
                Setting::set($key, $value, $group);

                if (array_key_exists($key, $rateToUnitTypeMap) && is_numeric($value) && (int) $value > 0) {
                    Tv::where('type', $rateToUnitTypeMap[$key])->update([
                        'price_per_hour' => (int) $value,
                    ]);
                }
            }
        });

        return back()->with('success', 'Pengaturan sistem dan tarif seluruh unit berhasil diperbarui!');
    }
}
