<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Todo\API\TodoAPIController;
use App\Http\Controllers\Auth\API\AuthAPIController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\AuthController;
use Domain\Admin\Controllers\AdminAuthController;
use Domain\Admin\Controllers\AdminTodoController;
use Domain\Admin\Controllers\UserStatsController;

// Handle CORS preflight request
Route::options('{any}', function() {
    return response('', 200)
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN')
        ->header('Access-Control-Allow-Origin', '*');
})->where('any', '.*');

// Regular User Authentication Routes (for Next.js and Flutter)
Route::post('/register', [AuthAPIController::class, 'register']);
Route::post('/login', [AuthAPIController::class, 'login']);

// Protected Regular User Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthAPIController::class, 'logout']);
    Route::get('/user', [AuthAPIController::class, 'user']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Todo Routes for Regular Users
    Route::get('/todos', [TodoAPIController::class, 'findall']);
    Route::get('/todos/{ownerId}', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle']);
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
    Route::get('/todos/{todo}', [TodoController::class, 'show']);
    Route::patch('/todos/{todo}', [TodoController::class, 'update']);
    Route::get('/todos/{ownerId}/completed', [TodoController::class, 'completed']);
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    
    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        
        // Admin Todo Routes
        Route::apiResource('todos', AdminTodoController::class);
        
        // User Stats Routes
        Route::get('/user-rankings', [UserStatsController::class, 'getUserRankings']);
    });
});
