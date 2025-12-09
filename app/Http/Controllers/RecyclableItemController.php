<?php

namespace App\Http\Controllers;

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
     * Record a recycling transaction.
     * Only the customer earns points.
     */
    public function recycle(Request $request, $id): JsonResponse
    {
        try {
            $customer = Auth::user(); // Logged-in customer
            $item = RecyclableItem::findOrFail($id);
            $pointsEarned = $item->points ?? 5;

            DB::transaction(function () use ($customer, $pointsEarned, $item) {

                // Add points to customer
                $customer->addPoints($pointsEarned);

                // Record the transaction (no employee)
                Transaction::create([
                    'user_id' => $customer->id,
                    'employee_id' => null,
                    'points_change' => $pointsEarned,
                    'type' => 'earned',
                    'description' => "Recycled item: {$item->name}"
                ]);
            });

            return response()->json([
                'message' => "You recycled '{$item->name}' and earned {$pointsEarned} points!",
                'user_points' => $customer->points
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to record recycling transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
