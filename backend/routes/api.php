<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Todo\API\TodoAPIController;
use App\Http\Controllers\Auth\API\AuthAPIController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test',function(){
    return response()->json(['message' => 'Hello, World!']);
});

Route::get('/todos', [TodoAPIController::class, 'findall']);

Route::post('/login', [AuthAPIController::class, 'login']);
Route::post('/register', [AuthAPIController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthAPIController::class, 'logout']);
    Route::get('/user', [AuthAPIController::class, 'user']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::post('/todos', [TodoController::class, 'store']);

Route::get('/todos/{ownerId}', [TodoController::class, 'index']);

Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle']);
Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);

Route::get('/todos/{todo}', [TodoController::class, 'show']);
Route::post('/todos/{todo}', [TodoController::class, 'update']);

// Route::get('/todos', [TodoController::class, 'index']);
// Route::get('/todos/{id}', [TodoController::class, 'show']);
// Route::post('/todos', [TodoController::class, 'store']);
// Route::put('/todos/{id}', [TodoController::class, 'update']);
// Route::delete('/todos/{id}', [TodoController::class, 'destroy']);

