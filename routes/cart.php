<?php

use App\Http\Controllers\Api\V1\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('cart.')->group(function () {
    Route::get('/cart', [CartController::class, 'show'])->name('show');
    Route::put('/cart', [CartController::class, 'update'])->name('update');
    Route::post('/cart/merge', [CartController::class, 'merge'])->name('merge');
    Route::delete('/cart', [CartController::class, 'destroy'])->name('destroy');
});
