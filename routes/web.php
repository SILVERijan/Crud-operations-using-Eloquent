<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::middleware('throttle:global')->group(function () {
    Route::get('/', function () {
        return redirect()->route('posts.index');
    });

    // Auth Routes
    Route::middleware(['guest', 'throttle:auth'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Protected Routes
    Route::middleware(['auth', 'throttle:60,1'])->group(function () {
        // Categories
        Route::resource('categories', CategoryController::class);

        // Posts
        Route::get('posts/liked', [App\Http\Controllers\LikeController::class, 'index'])->name('posts.liked');
        Route::post('posts/{post}/like', [App\Http\Controllers\LikeController::class, 'toggle'])->name('posts.like');
        Route::resource('posts', PostController::class);
    });
});