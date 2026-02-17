<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;

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

    // ============================================
    // ADMIN ROUTES - Full CRUD on all resources
    // ============================================
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\DashboardController::class, 'userDetail'])->name('users.show');
        Route::get('/posts', [App\Http\Controllers\Admin\DashboardController::class, 'posts'])->name('posts');
        Route::get('/categories', [App\Http\Controllers\Admin\DashboardController::class, 'categories'])->name('categories');
    });

    // ============================================
    // CUSTOMER ROUTES - Form management only
    // ============================================
    Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
        // Customers can create, view, and delete their own forms
        Route::get('/forms', [FormController::class, 'index'])->name('forms.index');
        Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create');
        Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
        Route::get('/forms/{form}', [FormController::class, 'show'])->middleware('owns:form')->name('forms.show');
        Route::delete('/forms/{form}', [FormController::class, 'destroy'])->middleware('owns:form')->name('forms.destroy');
    });

    // ============================================
    // PUBLIC POSTS - All authenticated users can view
    // CRUD operations controlled by PostPolicy
    // ============================================
    Route::middleware(['auth', 'throttle:60,1'])->group(function () {
        // Post creation (must be before show route to avoid collision)
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

        // Post likes (must be before show route to avoid collision)
        Route::get('posts/liked', [App\Http\Controllers\LikeController::class, 'index'])->name('posts.liked');
        Route::post('posts/{post}/like', [App\Http\Controllers\LikeController::class, 'toggle'])->name('posts.like');
        
        // Posts - PUBLIC viewing and CRUD
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

        // Categories - accessible to all authenticated users
        Route::resource('categories', CategoryController::class);
    });
});