<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecyclableItem;

class RecyclableItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the bottles/items
        $items = [
            [
                'name' => 'Sprite Energy Drink',
                'desc' => 'Bottle available for recycling and points',
                'img' => '/images/bottles/derrick-payton-s6xYsISI9Zk-unsplash.jpg',
            ],
            [
                'name' => 'Coca-Cola',
                'desc' => 'Bottle available for recycling',
                'img' => '/images/bottles/easylife-designs-IJlyaf4q0_s-unsplash.jpg',
            ],
            [
                'name' => 'Mountain Dew',
                'desc' => 'Bottle available for recycling',
                'img' => '/images/bottles/erik-mclean-5JdKoyIKWW4-unsplash.jpg',
            ],
            [
                'name' => 'Wisers Beer',
                'desc' => 'Bottle available for recycling',
                'img' => '/images/bottles/shen-liu-J-UKLgHEotw-unsplash.jpg',
            ],
            [
                'name' => 'Pepsi',
                'desc' => 'Bottle available for recycling',
                'img' => '/images/nikhil-82LJQZGwW5o-unsplash.jpg',
            ],
        ];

        // Insert each item into the database
        foreach ($items as $item) {
            RecyclableItem::updateOrCreate(
                ['name' => $item['name']], // Avoid duplicate entries
                $item
            );
        }
    }
}
