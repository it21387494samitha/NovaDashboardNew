<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Samitha\TestingCompo\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the ServiceProvider and prefixed with:
|   /nova-vendor/testing-compo/
|
| So Route::get('/orders', ...) becomes:
|   GET /nova-vendor/testing-compo/orders
|
| The Vue component calls these endpoints using Nova.request() which
| automatically includes CSRF tokens and auth headers.
|
*/

// List orders with filters + pagination
Route::get('/orders', [OrderController::class, 'index']);

// Aggregated stats for charts
Route::get('/orders/stats', [OrderController::class, 'stats']);
