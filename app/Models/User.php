<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'role',
        'points',
    ];

    /**
     * Hidden attributes for arrays or JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Add points to the user.
     */
    public function addPoints(int $points): void
    {
        $this->increment('points', $points);
    }

    /**
     * Remove points from the user.
     */
    public function removePoints(int $points): void
    {
        $this->decrement('points', $points);
    }

    /**
     * Role helpers.
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

    /**
     * Transactions where the user is the customer.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Transactions processed by this employee.
     */
    public function processedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'employee_id');
    }
}
