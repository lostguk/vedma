<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {});

// Health check для Docker
Route::get('/health', function () {
    return response('healthy', 200)
        ->header('Content-Type', 'text/plain');
});
