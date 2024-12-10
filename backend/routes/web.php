<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\Dashboard\WEB\DashboardWEBController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('validate.login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.view');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard routes
    Route::get('/dashboard', [DashboardWEBController::class, 'index'])->name('dashboard');
    Route::get('/leaderboard', [DashboardWEBController::class, 'leaderboard'])->name('leaderboard');

    // Todo routes
    Route::resource('todos', TodoController::class);
    Route::post('/todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
});
