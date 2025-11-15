<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\HomePageContentController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ShippingController;
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
    })->name('api.v1.health');

    // Auth
    require base_path('routes/auth.php');

    // User
    require base_path('routes/user.php');

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
    Route::get('categories/{slug}', [CategoryController::class, 'show'])->name('api.v1.categories.show');

    // Products
    Route::get('products/{slug}', [ProductController::class, 'show'])
        ->name('api.v1.products.show');
    Route::get('products', [ProductController::class, 'index'])->name('api.v1.products.index');

    // Mail test
    Route::get('mail/test', [MailController::class, 'sendTestMail'])->name('mail.test');

    // Pages
    Route::name('pages.')->group(function () {
        Route::get('/pages', [PageController::class, 'index'])->name('index');
        Route::get('/pages/{id}', [PageController::class, 'show'])->name('show');
    });

    // Order
    Route::post('order/calculate', [OrderController::class, 'calculate'])
        ->name('api.v1.order.calculate');

    Route::middleware('auth:sanctum')->get('orders', [OrderController::class, 'index'])
        ->name('api.v1.orders.index');

    Route::post('order', [OrderController::class, 'store'])
        ->name('api.v1.order.store');

    // Shipping calculation
    Route::post('shipping/calculate', [ShippingController::class, 'calculate'])
        ->name('api.v1.shipping.calculate');

    // Home page
    Route::get('home', [HomePageContentController::class, 'show'])
        ->name('api.v1.home.show');
});
