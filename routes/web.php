<?php

use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SystemController::class, 'index']);

// Health check для Docker
Route::get('/health', [SystemController::class, 'health']);
