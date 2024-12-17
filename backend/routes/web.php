<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Domain\Admin\Controllers\AdminAuthController;
use Domain\Admin\Controllers\AdminDashboardController;
use Domain\Admin\Controllers\AdminTodoController;
use Domain\Admin\Controllers\UserStatsController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\Dashboard\WEB\DashboardWEBController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Admin Auth Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [AdminAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AdminAuthController::class, 'register'])->name('register.submit');
    });

    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Admin Todo Routes
        Route::resource('todos', AdminTodoController::class);
        
        // User Rankings
        Route::get('/user-rankings', [UserStatsController::class, 'getUserRankings'])->name('user-rankings');
    });
});

// Regular User Routes
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Redirect all other routes to admin login for now
Route::fallback(function () {
    return redirect()->route('admin.login');
});
