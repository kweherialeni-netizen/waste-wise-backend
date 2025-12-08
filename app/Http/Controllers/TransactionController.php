<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Admin: List all transactions with user and employee info
     */
    public function index(): JsonResponse
    {
        try {
            $transactions = Transaction::with([
                'user:id,name,email',
                'employee:id,name,email'
            ])->latest()->get();

            return response()->json([
                'message' => 'Transactions retrieved successfully.',
                'data' => $transactions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve transactions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin/Employee: Create a transaction for a user
     * Employees can add or deduct points for a user
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points_change' => 'required|integer',
            'type' => 'required|in:earned,spent',
            'description' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                $user = User::findOrFail($validatedData['user_id']);
                $employee = Auth::user(); // The logged-in employee

                // Adjust points for spent type
                if ($validatedData['type'] === 'spent') {
                    $validatedData['points_change'] = -abs($validatedData['points_change']);
                    $user->removePoints(abs($validatedData['points_change']));
                } else {
                    $user->addPoints($validatedData['points_change']);
                }

                // Record the transaction including employee
                Transaction::create([
                    'user_id' => $user->id,
                    'employee_id' => $employee->id,
                    'points_change' => $validatedData['points_change'],
                    'type' => $validatedData['type'],
                    'description' => $validatedData['description'] ?? ''
                ]);
            });

            return response()->json([
                'message' => 'Transaction created and points updated successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logged-in user: List their transactions and current points
     */
    public function myTransactions(): JsonResponse
    {
        try {
            $user = Auth::user();

            $transactions = Transaction::with('employee:id,name,email')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return response()->json([
                'message' => 'Your transactions retrieved successfully.',
                'data' => $transactions,
                'points' => $user->points
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve your transactions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Update a transaction
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'points_change' => 'integer',
            'description' => 'nullable|string',
            'type' => 'in:earned,spent'
        ]);

        try {
            $transaction = Transaction::findOrFail($id);

            // Optional: adjust user points if points_change is updated
            if (isset($validatedData['points_change'])) {
                $user = $transaction->user;
                $diff = $validatedData['points_change'] - $transaction->points_change;
                $user->addPoints($diff);
            }

            $transaction->update($validatedData);

            return response()->json([
                'message' => 'Transaction updated successfully.',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Delete a transaction
     */
    public function destroy($id): JsonResponse
    {
        try {
            $transaction = Transaction::findOrFail($id);

            // Optionally revert points
            $user = $transaction->user;
            $user->removePoints($transaction->points_change);

            $transaction->delete();

            return response()->json([
                'message' => 'Transaction deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
