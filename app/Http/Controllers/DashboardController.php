<?php

namespace App\Http\Controllers;

use App\Http\Resources\TvResource;
use App\Models\Tv;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardController extends Controller
{
    /**
     * Menampilkan semua data TV beserta active session (jika ada).
     */
    public function index(): AnonymousResourceCollection
    {
        $tvs = Tv::with(['playSessions' => function ($query) {
            $query->where('status', 'active')->with(['user', 'sessionOrders.product']);
        }])->get();

        return TvResource::collection($tvs);
    }
}
