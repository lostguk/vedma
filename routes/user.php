<?php

use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TopicController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show'])->name('user.profile');
Route::middleware('auth:sanctum')->patch('/profile', [ProfileController::class, 'update'])->name('user.profile.update');

// User topics
Route::middleware('auth:sanctum')->name('user.topics.')->group(function () {
    Route::get('/topics', [TopicController::class, 'index'])->name('index');
    Route::get('/topics/{id}', [TopicController::class, 'show'])->name('show');
});
