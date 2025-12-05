<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RecyclableItemController;

// -----------------------------
// Public Authentication Routes
// -----------------------------
Route::post('/register', [AuthController::class, 'register']); // Register a new user
Route::post('/login', [AuthController::class, 'login']);       // Login existing user

// -----------------------------
// Protected Routes (Sanctum)
// -----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']); // Logout current user

    // -----------------------------
    // User Routes
    // -----------------------------
    Route::get('/users', [UserController::class, 'index']);      // List all users
    Route::get('/users/{id}', [UserController::class, 'show']); // Get specific user
    Route::post('/users', [UserController::class, 'store']);    // Create new user
    Route::put('/users/{id}', [UserController::class, 'update']);   // Update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user

    // -----------------------------
    // Rewards Routes
    // -----------------------------
    Route::get('/rewards', [RewardController::class, 'index']);       // List all rewards
    Route::post('/rewards', [RewardController::class, 'store']);      // Create new reward
    Route::put('/rewards/{id}', [RewardController::class, 'update']); // Update reward
    Route::delete('/rewards/{id}', [RewardController::class, 'destroy']); // Delete reward


    // Recyclable Items Routes
    // -----------------------------
    Route::get('/items', [RecyclableItemController::class, 'index']);       // List all items
    Route::get('/items/{id}', [RecyclableItemController::class, 'show']);   // Get single item
    Route::post('/items', [RecyclableItemController::class, 'store']);      // Create new item
    Route::put('/items/{id}', [RecyclableItemController::class, 'update']); // Update item
    Route::delete('/items/{id}', [RecyclableItemController::class, 'destroy']); // Delete item

    // -----------------------------
    // Admin-only Routes
    // -----------------------------
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Admin Access Granted']); // Example admin route
        });
    });

});
