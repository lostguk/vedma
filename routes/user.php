<?php

use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TopicController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('user.profile')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('update');
});

// User topics
Route::middleware('auth:sanctum')->name('user.topics.')->group(function () {
    Route::get('/topics', [TopicController::class, 'index'])->name('index');
    Route::post('/topics', [TopicController::class, 'store'])->name('store');
    Route::get('/topics/unread-count', [TopicController::class, 'unreadCount'])->name('unread-count');
    Route::get('/topics/{topicId}', [TopicController::class, 'show'])->name('show');
    Route::post('/topics/{topicId}/messages', [TopicController::class, 'addMessage'])->name('messages.store');
});
