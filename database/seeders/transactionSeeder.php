<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        Transaction::create([
            'user_id' => 3, // customer id
            'points_change' => 50,
            'type' => 'earned',
            'description' => 'Recycled aluminium cans'
        ]);

        Transaction::create([
            'user_id' => 3,
            'points_change' => -150,
            'type' => 'spent',
            'description' => 'Redeemed eco bag'
        ]);
    }
}
