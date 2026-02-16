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

    // Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\DashboardController::class, 'userDetail'])->name('users.show');
        Route::get('/posts', [App\Http\Controllers\Admin\DashboardController::class, 'posts'])->name('posts');
        Route::get('/categories', [App\Http\Controllers\Admin\DashboardController::class, 'categories'])->name('categories');
    });
});