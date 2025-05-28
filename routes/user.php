<?php

use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show'])->name('user.profile');
