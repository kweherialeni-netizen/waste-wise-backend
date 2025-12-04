<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\recyclable;

class RecyclableSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['name' => 'Plastic Bottle', 'points' => 5],
            ['name' => 'Glass Bottle', 'points' => 8],
            ['name' => 'Aluminium Can', 'points' => 10],
            ['name' => 'Cardboard', 'points' => 3],
            ['name' => 'Paper', 'points' => 2]
        ];

        foreach ($items as $item) {
            Recyclable::create($item);
        }
    }
}
