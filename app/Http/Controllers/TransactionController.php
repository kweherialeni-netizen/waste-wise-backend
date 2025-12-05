<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // List all transactions along with the user who made them
    public function index(): JsonResponse
    {
        try {
            // Retrieve all transactions with only necessary user info to avoid exposing sensitive data
            $transactions = Transaction::with(['user' => function ($query) {
                $query->select('id', 'name', 'email'); // only return id, name, email
            }])->get();

            return response()->json([
                'message' => 'Transactions retrieved successfully.',
                'data' => $transactions
            ], 200);
        } catch (\Exception $e) {
            // Return error if something goes wrong
            return response()->json([
                'message' => 'Failed to retrieve transactions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Store a new transaction
    public function store(Request $request): JsonResponse
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',   // user must exist
            'points_change' => 'required|integer',     // points added or spent
            'type' => 'required|in:earned,spent',      // transaction type
            'description' => 'nullable|string',        // optional description
        ]);

        try {
            // Optionally, automatically convert spent points to negative
            if ($validatedData['type'] === 'spent') {
                $validatedData['points_change'] = -abs($validatedData['points_change']);
            }

            // Use transaction to ensure database consistency
            $transaction = DB::transaction(function () use ($validatedData) {
                return Transaction::create($validatedData);
            });

            return response()->json([
                'message' => 'Transaction created successfully.',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            // Return error if creation fails
            return response()->json([
                'message' => 'Failed to create transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
