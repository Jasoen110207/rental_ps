<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Tv;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $settings = Setting::whereIn('key', ['store_name', 'store_phone', 'store_address'])->pluck('value', 'key');
        $labels = ['ps3' => 'PlayStation 3', 'ps4' => 'PlayStation 4', 'ps5' => 'PlayStation 5', 'sim_racing' => 'Sim Racing', 'nintendo_switch' => 'Nintendo Switch'];
        $units = Tv::query()->selectRaw('type, COUNT(*) as total, MIN(price_per_hour) as price')
            ->groupBy('type')->get()->sortBy(fn ($unit) => array_search($unit->type, array_keys($labels)));
        $phone = preg_replace('/\D/', '', $settings['store_phone'] ?? '');
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }
        $contactUrl = strlen($phone) >= 9 && strlen($phone) <= 15
            ? 'https://wa.me/'.$phone.'?text='.rawurlencode('Halo, saya ingin tanya ketersediaan unit dan booking di TambahBang.')
            : null;

        return view('landing', compact('settings', 'labels', 'units', 'contactUrl'));
    }
}
