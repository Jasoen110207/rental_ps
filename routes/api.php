<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlaySessionController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::post('/play-sessions', [PlaySessionController::class, 'store']);
Route::put('/play-sessions/{playSession}', [PlaySessionController::class, 'update']);
Route::post('/play-sessions/{id}/orders', [OrderController::class, 'store']);

