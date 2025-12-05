<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\reward;

class RewardSeeder extends Seeder
{
    public function run()
    {
        $rewards = [
            ['name' => 'beer bottle', 'cost' => 150],
            ['name' => 'Water Bottle', 'cost' => 300],
            ['name' => 'pepsi bottle', 'cost' => 200],
            ['name' => 'coke bottle', 'cost' => 500],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
