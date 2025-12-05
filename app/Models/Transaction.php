<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory; // Allows using factories for testing and seeding

    // Attributes that can be mass-assigned
    protected $fillable = [
        'user_id',       // ID of the user who made the transaction
        'points_change', // Number of points earned or spent
        'type',          // Transaction type: 'earned' or 'spent'
        'description',   // Optional description of the transaction
    ];

    // Define relationship to the User model
    public function user()
    {
        // Each transaction belongs to one user
        return $this->belongsTo(User::class);
    }
}
