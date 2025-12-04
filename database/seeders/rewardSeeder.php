<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\reward;

class RewardSeeder extends Seeder
{
    public function run()
    {
        $rewards = [
            ['name' => 'Eco Bag', 'cost' => 150],
            ['name' => 'Water Bottle', 'cost' => 300],
            ['name' => 'Discount Voucher KES 100', 'cost' => 200],
            ['name' => 'T-Shirt', 'cost' => 500],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
