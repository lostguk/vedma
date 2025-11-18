<?php

use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('order/calculate', [OrderController::class, 'calculate'])
    ->name('api.v1.order.calculate');

Route::middleware('auth:sanctum')->get('orders', [OrderController::class, 'index'])
    ->name('api.v1.orders.index');

Route::post('order', [OrderController::class, 'store'])
    ->name('api.v1.order.store');
