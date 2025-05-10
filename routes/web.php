<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CommentController;
use App\Http\Middleware\SuperAdminMiddleware;

Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Social login routes
Route::middleware('guest')->group(function () {
    Route::get('login', [SocialiteController::class, 'showLogin'])
        ->name('login');
    
    Route::get('/auth/{provider}', [SocialiteController::class, 'redirect']);
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback']);
});

// posts routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// SuperAdmin routes
Route::middleware(['auth', SuperAdminMiddleware::class])->group(function () {
    Route::get('/admin/users', [SuperAdminController::class, 'getAllUsers'])->name('admin.users');
    Route::delete('/admin/users/{id}', [SuperAdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::put('/admin/users/{id}/role', [SuperAdminController::class, 'updateUserRole'])->name('admin.users.updateRole');
});

// public routes
Route::get('/home', [HomePageController::class, 'index'])->name('home');

// comment routes
Route::post('/posts/{post}/comments', [CommentController::class, 'storeNewComment'])->name('posts.comments.store');

require __DIR__.'/auth.php';
