<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal (default Laravel welcome page)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ==========================
// CUSTOMER (Console Pelanggan)
// resources/views/customer/*.blade.php
// ==========================
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/order', function () {
        return view('customer.order');
    })->name('order');
});

// ==========================
// KASIR (Dashboard Admin/Kasir)
// resources/views/kasir/*.blade.php
// ==========================
Route::prefix('kasir')->name('kasir.')->group(function () {

    Route::get('/dashboard', function () {
        return view('kasir.dashboard');
    })->name('dashboard');

    Route::get('/pos', function () {
        return view('kasir.pos');
    })->name('pos');

    Route::get('/menu', function () {
        return view('kasir.menu');
    })->name('menu');

    Route::get('/request', function () {
        return view('kasir.request');
    })->name('request');

    Route::get('/setting', function () {
        return view('kasir.setting');
    })->name('setting');

    Route::get('/sift', function () {
        return view('kasir.sift');
    })->name('sift');

    Route::get('/transaksi', function () {
        return view('kasir.transaksi');
    })->name('transaksi');

    Route::get('/unit', function () {
        return view('kasir.unit');
    })->name('unit');

});