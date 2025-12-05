<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RecyclableItemController;

// -----------------------------
// Public Authentication Routes
// -----------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// -----------------------------
// Protected Routes (Sanctum)
// -----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // -----------------------------
    // User Routes
    // -----------------------------
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // -----------------------------
    // Rewards Routes
    // -----------------------------
    Route::get('/rewards', [RewardController::class, 'index']);
    Route::post('/rewards', [RewardController::class, 'store']);
    Route::put('/rewards/{id}', [RewardController::class, 'update']);
    Route::delete('/rewards/{id}', [RewardController::class, 'destroy']);

    // -----------------------------
    // Recyclable Items Routes
    // -----------------------------
    Route::get('/items', [RecyclableItemController::class, 'index']);
    Route::post('/items', [RecyclableItemController::class, 'store']);
    Route::get('/items/{id}', [RecyclableItemController::class, 'show']);
    Route::put('/items/{id}', [RecyclableItemController::class, 'update']);
    Route::delete('/items/{id}', [RecyclableItemController::class, 'destroy']);

    // -----------------------------
    // Admin-only Routes
    // -----------------------------
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Admin Access Granted']);
        });
    });

});
