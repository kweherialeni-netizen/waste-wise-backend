<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Enables API token authentication

class User extends Authenticatable
{
    // Traits to add functionality to the User model
    use HasApiTokens, HasFactory, Notifiable;

    // Attributes that can be mass-assigned
    protected $fillable = [
        'name',     // User's full name
        'email',    // User's email address
        'password', // User's password
    ];

    // Attributes that should be hidden when converting to arrays or JSON
    protected $hidden = [
        'password',       // Hide password for security
        'remember_token', // Hide remember token
    ];

    // Attribute casting for automatic type conversion
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Convert to Carbon instance
            'password' => 'hashed',            // Automatically hash password
        ];
    }

 
}
