<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::with('user')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points_change' => 'required|integer',
            'type' => 'required|in:earned,spent',
            'description' => 'nullable|string',
        ]);

        return Transaction::create($data);
    }
}
