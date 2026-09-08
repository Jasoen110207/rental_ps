<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RequestCenterController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TambahBang Rental PS
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Customer Routes (Accessed via QR Code - No Login Required)
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/order', function () {
        return view('customer.order');
    })->name('order');
    Route::get('/{tv_id}', [CustomerController::class, 'index'])->name('index');
    Route::post('/{tv_id}/add-time', [CustomerController::class, 'requestAddTime'])->name('add-time');
    Route::post('/{tv_id}/order-food', [CustomerController::class, 'requestFood'])->name('order-food');
    Route::get('/{tv_id}/status', [CustomerController::class, 'apiStatus'])->name('status');
});
// QR short url alias
Route::get('/scan/{tv_id}', function ($tv_id) {
    return redirect()->route('customer.index', ['tv_id' => $tv_id]);
});

// Protected Cashier / Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Operational Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api-status', [DashboardController::class, 'apiStatus'])->name('dashboard.api-status');

    // Rental / Session Controls
    Route::post('/rental/start', [RentalController::class, 'start'])->name('rental.start');
    Route::post('/rental/{sessionId}/extend', [RentalController::class, 'extend'])->name('rental.extend');
    Route::post('/rental/{sessionId}/add-fnb', [RentalController::class, 'addFnb'])->name('rental.add-fnb');
    Route::post('/rental/{sessionId}/checkout', [RentalController::class, 'checkout'])->name('rental.checkout');
    Route::post('/rental/{tvId}/toggle-buzzer', [RentalController::class, 'toggleBuzzer'])->name('rental.toggle-buzzer');

    // Customer Requests Center
    Route::get('/requests', [RequestCenterController::class, 'index'])->name('requests.index');
    Route::post('/requests/{id}/approve', [RequestCenterController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{id}/reject', [RequestCenterController::class, 'reject'])->name('requests.reject');

    // F&B Catalog POS & Ordering
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/order', [PosController::class, 'storeOrder'])->name('pos.order');
    Route::get('/pos/manage', [PosController::class, 'manage'])->name('pos.manage');
    Route::post('/pos/save-product', [PosController::class, 'saveProduct'])->name('pos.save-product');
    Route::post('/pos/product/{id}/toggle', [PosController::class, 'toggleProduct'])->name('pos.product.toggle');

    // Transactions History & Invoicing
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{id}/invoice', [TransactionController::class, 'printInvoice'])->name('transactions.invoice');

    // Shift Management
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shifts/start', [ShiftController::class, 'startShift'])->name('shifts.start');
    Route::post('/shifts/{id}/end', [ShiftController::class, 'endShift'])->name('shifts.end');

    // Unit Management & QR Generator
    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::put('/units/{id}', [UnitController::class, 'update'])->name('units.update');
    Route::post('/units/{id}/toggle-status', [UnitController::class, 'toggleStatus'])->name('units.toggle-status');
    Route::get('/units/{id}/qr', [UnitController::class, 'qr'])->name('units.qr');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Simple Kasir view routes (legacy / mockup)
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
