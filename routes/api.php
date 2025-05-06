<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check endpoint
Route::get('/health', HealthController::class);

Route::prefix('v1')->group(function () {
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json(['status' => 'ok']);
    });

    // Auth
    Route::post('register', RegisterController::class)->name('api.v1.auth.register');
    Route::post('login', LoginController::class)->name('api.v1.auth.login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', LogoutController::class)->name('api.v1.auth.logout');
    });

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
    Route::get('categories/{slug}', [CategoryController::class, 'show'])->name('api.v1.categories.show');

    // Products
    Route::get('products/{slug}', [ProductController::class, 'show'])
        ->name('api.v1.products.show');
    Route::get('products', [ProductController::class, 'index'])->name('api.v1.products.index');

    Route::get('mail/test', [MailController::class, 'testMail'])->name('mail.test');
});
