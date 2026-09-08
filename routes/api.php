<?php

use App\Http\Controllers\CustomerRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlaySessionController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\CheckRoleAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::post('/play-sessions', [PlaySessionController::class, 'store']);
Route::put('/play-sessions/{playSession}', [PlaySessionController::class, 'update']);
Route::post('/play-sessions/{id}/orders', [OrderController::class, 'store']);

// Endpoint Request Pelanggan (QR Code di Meja)
Route::post('/customer-requests', [CustomerRequestController::class, 'store']);
Route::get('/customer-requests', [CustomerRequestController::class, 'index']);
Route::put('/customer-requests/{customerRequest}', [CustomerRequestController::class, 'update']);

// Endpoint Laporan Pendapatan (Admin Only)
Route::get('/reports/revenue', [ReportController::class, 'index'])->middleware(CheckRoleAdmin::class);


