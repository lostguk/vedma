<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Магазин магических товаров API',
        'version' => '1.0.0',
        'status' => 'working',
        'timestamp' => now()->toISOString()
    ]);
});

// Health check для Docker
Route::get('/health', function () {
    return response('healthy', 200)
        ->header('Content-Type', 'text/plain');
});
