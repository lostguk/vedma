<?php

use App\Http\Controllers\Api\V1\Auth\ChangePasswordController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResendVerificationController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\VerifyRegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class)->name('api.v1.auth.register');
Route::post('login', LoginController::class)->name('api.v1.auth.login');
Route::post('forgot-password', ForgotPasswordController::class)->name('api.v1.auth.forgot-password');
Route::post('reset-password', ResetPasswordController::class)->name('api.v1.auth.reset-password');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', LogoutController::class)->name('api.v1.auth.logout');
    Route::post('change-password', ChangePasswordController::class)->name('api.v1.auth.change-password');
});

Route::get('verify-registration/{user}/{hash}', VerifyRegistrationController::class)
    ->middleware('signed')
    ->name('api.v1.auth.verify-registration');

Route::post('verify-registration/resend', ResendVerificationController::class)
    ->name('api.v1.auth.verify-registration.resend');
