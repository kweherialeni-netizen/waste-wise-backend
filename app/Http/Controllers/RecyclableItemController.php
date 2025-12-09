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
     * User records a recycling transaction.
     * Only the logged-in user earns points.
     */
    public function recycle(Request $request, $id): JsonResponse
    {
        $user = Auth::user(); // The logged-in user

        try {
            $item = RecyclableItem::findOrFail($id);
            $pointsEarned = $item->points ?? 5; // Default points if not set

            DB::transaction(function () use ($user, $pointsEarned, $item) {
                // Add points to user
                $user->addPoints($pointsEarned);

                // Record the transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'points_change' => $pointsEarned,
                    'type' => 'earned',
                    'description' => "Recycled item: {$item->name}"
                ]);
            });

            return response()->json([
                'message' => "You recycled '{$item->name}' and earned {$pointsEarned} points!",
                'user_points' => $user->points
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to record recycling transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
