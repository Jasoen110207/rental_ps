<?php

use App\Models\Tv;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-tampilan', function () {
    $tvs = Tv::all();
    $products = Product::all();
    
    return view('test-tampilan', compact('tvs', 'products'));
});