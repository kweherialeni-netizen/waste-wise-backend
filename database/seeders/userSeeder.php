<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@wastewise.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'points' => 0
        ]);

        // Employee
        User::create([
            'name' => 'Store Employee',
            'email' => 'employee@wastewise.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'points' => 0
        ]);

        // Customer
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@wastewise.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'points' => 200
        ]);
    }
}
