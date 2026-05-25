<?php

use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('payments', [PaymentController::class, 'store'])
    ->name('api.v1.payments.store');

Route::get('payments/{payment}/status', [PaymentController::class, 'status'])
    ->name('api.v1.payments.status');

Route::post('payments/{payment}/refund', [PaymentController::class, 'refund'])
    ->name('api.v1.payments.refund');

Route::post('payments/alfabank/webhook', [PaymentController::class, 'webhook'])
    ->name('api.v1.payments.alfabank.webhook');
