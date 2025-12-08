<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Default attribute values for new users.
     */
    protected $attributes = [
        'role' => 'user',   // default role
        'points' => 0,      // default points
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',    // include role for assignment
        'points',
    ];

    /**
     * The attributes that should be hidden for arrays or JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting for automatic type conversion.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Add points to the user.
     *
     * @param int $points
     * @return void
     */
    public function addPoints(int $points): void
    {
        $this->points += $points;
        $this->save();
    }

    /**
     * Helper methods for checking roles.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
