<?php

use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TopicController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show'])->name('user.profile');
Route::middleware('auth:sanctum')->patch('/profile', [ProfileController::class, 'update'])->name('user.profile.update');

// User topics
Route::middleware('auth:sanctum')->name('user.topics.')->group(function () {
    Route::get('/topics', [TopicController::class, 'index'])->name('index');
    Route::post('/topics', [TopicController::class, 'store'])->name('store');
    Route::get('/topics/{topicId}', [TopicController::class, 'show'])->name('show');
    Route::post('/topics/{topicId}/messages', [TopicController::class, 'addMessage'])->name('messages.store');
});
