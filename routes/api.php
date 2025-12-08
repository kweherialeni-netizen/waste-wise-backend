<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RecyclableItemController;
use App\Http\Controllers\TransactionController;

// -----------------------------
// Public Authentication Routes
// -----------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// -----------------------------
// Public Recyclable Items Routes
// -----------------------------
Route::get('/items', [RecyclableItemController::class, 'index']);       // List all items
Route::get('/items/{id}', [RecyclableItemController::class, 'show']);  // Get single item

// -----------------------------
// Protected Routes (Sanctum)
// -----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);

    // -----------------------------
    // User CRUD (admin/employee can manage)
    // -----------------------------
    Route::get('/users', [UserController::class, 'index']);       // List all users
    Route::get('/users/{id}', [UserController::class, 'show']);   // Single user
    Route::post('/users', [UserController::class, 'store']);      // Create user
    Route::put('/users/{id}', [UserController::class, 'update']); // Update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user

    // -----------------------------
    // Logged-in user's profile & transactions
    // -----------------------------
    Route::get('/profile', [TransactionController::class, 'myTransactions']); 
    // Returns logged-in user's points and transaction history

    // -----------------------------
    // Rewards CRUD
    // -----------------------------
    Route::get('/rewards', [RewardController::class, 'index']);
    Route::post('/rewards', [RewardController::class, 'store']);
    Route::put('/rewards/{id}', [RewardController::class, 'update']);
    Route::delete('/rewards/{id}', [RewardController::class, 'destroy']);

    // -----------------------------
    // Recyclable Items CRUD (protected)
    // -----------------------------
    Route::post('/items', [RecyclableItemController::class, 'store']);
    Route::put('/items/{id}', [RecyclableItemController::class, 'update']);
    Route::delete('/items/{id}', [RecyclableItemController::class, 'destroy']);

    // -----------------------------
    // Recycle an item (user action)
    // -----------------------------
    Route::post('/items/{id}/recycle', [RecyclableItemController::class, 'recycle']);

    // -----------------------------
    // Transactions (admin view / manage)
    // -----------------------------
    Route::get('/transactions', [TransactionController::class, 'index']); // View all transactions
    Route::post('/transactions', [TransactionController::class, 'store']); // Create a transaction
    Route::put('/transactions/{id}', [TransactionController::class, 'update']); // Update transaction
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']); // Delete transaction

    // -----------------------------
    // Admin-only routes
    // -----------------------------
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Admin Access Granted']);
        });
    });
});
