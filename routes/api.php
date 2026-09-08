<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlaySessionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

// === Route Publik (Tanpa Login) ===
Route::post('/login', [AuthController::class, 'login']);

// Endpoint Publik Pelanggan (via QR Code di Meja)
Route::post('/customer-requests', [CustomerRequestController::class, 'store']);

// === Route Terproteksi (Kasir & Admin) ===
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Play Sessions
    Route::post('/play-sessions', [PlaySessionController::class, 'store']);
    Route::get('/play-sessions/{id}', [PlaySessionController::class, 'show']);
    Route::put('/play-sessions/{playSession}', [PlaySessionController::class, 'update']);
    Route::post('/play-sessions/{id}/orders', [OrderController::class, 'store']);

    // Customer Requests (Kasir Review)
    Route::get('/customer-requests', [CustomerRequestController::class, 'index']);
    Route::patch('/customer-requests/{id}/approve', [CustomerRequestController::class, 'approve']);
    Route::patch('/customer-requests/{id}/reject', [CustomerRequestController::class, 'reject']);
    Route::put('/customer-requests/{customerRequest}', [CustomerRequestController::class, 'update']);

    // Shift Management
    Route::post('/shifts', [ShiftController::class, 'store']);
    Route::put('/shifts', [ShiftController::class, 'update']);
    Route::get('/shifts', [ShiftController::class, 'index']);

    // Reports (Admin Only)
    Route::get('/reports/revenue', [ReportController::class, 'index'])->middleware('role:admin');
});
