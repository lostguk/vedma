<?php

use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
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

Route::prefix('v1')->group(function () {
    Route::post('register', RegisterController::class)->name('api.v1.auth.register');
    Route::get('categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
    Route::get('categories/{slug}', [CategoryController::class, 'show'])->name('api.v1.categories.show');
    Route::get('products/{slug}', [ProductController::class, 'show'])
        ->name('api.v1.products.show');
    Route::get('products', [ProductController::class, 'index'])->name('api.v1.products.index');
});
