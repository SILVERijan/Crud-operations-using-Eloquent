<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Categories
    Route::resource('categories', CategoryController::class)->except(['show', 'edit', 'update', 'destroy']);
    Route::middleware('owns:category')->group(function () {
        Route::resource('categories', CategoryController::class)->only(['show', 'edit', 'update', 'destroy']);
    });

    // Posts
    Route::resource('posts', PostController::class)->except(['show', 'edit', 'update', 'destroy'])->missing(function(){
         return redirect()->route('posts.index');
    });
    Route::middleware('owns:post')->group(function () {
        Route::resource('posts', PostController::class)->only(['show', 'edit', 'update', 'destroy']);
    });
});
