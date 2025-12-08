<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RecyclableItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecyclableItemController extends Controller
{
    /**
     * List all recyclable items.
     */
    public function index(): JsonResponse
    {
        try {
            $items = RecyclableItem::all();
            return response()->json([
                'message' => 'Recyclable items retrieved successfully.',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve items.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a single item by ID.
     */
    public function show($id): JsonResponse
    {
        try {
            $item = RecyclableItem::findOrFail($id);
            return response()->json([
                'message' => 'Item retrieved successfully.',
                'data' => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Item not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Employee records a recycling transaction.
     * Both the customer and employee earn points.
     */
    public function recycle(Request $request, $id): JsonResponse
    {
        $customer = Auth::user(); // The user recycling the item
        $employeeId = $request->input('employee_id'); // Employee recording the transaction

        if (!$employeeId) {
            return response()->json([
                'message' => 'Employee ID is required to confirm the transaction.'
            ], 422);
        }

        $employee = User::find($employeeId);

        if (!$employee || !$employee->isEmployee()) {
            return response()->json([
                'message' => 'Invalid employee.'
            ], 422);
        }

        try {
            $item = RecyclableItem::findOrFail($id);
            $pointsEarned = $item->points ?? 5; // Default points if not set

            DB::transaction(function () use ($customer, $employee, $pointsEarned, $item) {
                // Add points to customer
                $customer->addPoints($pointsEarned);

                // Add points to employee for processing
                $employee->addPoints($pointsEarned);

                // Record the transaction
                Transaction::create([
                    'user_id' => $customer->id,
                    'employee_id' => $employee->id,
                    'points_change' => $pointsEarned,
                    'type' => 'earned',
                    'description' => "Recycled item: {$item->name} confirmed by {$employee->name}"
                ]);
            });

            return response()->json([
                'message' => "You recycled '{$item->name}' and earned {$pointsEarned} points! Confirmed by employee {$employee->name}.",
                'user_points' => $customer->points,
                'employee_points' => $employee->points
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to record recycling transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
