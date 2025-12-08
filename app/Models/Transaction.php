<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',       // ID of the user who made the transaction
        'employee_id',   // ID of the employee who processed the transaction
        'points_change', // Number of points earned or spent
        'type',          // Transaction type: 'earned' or 'spent'
        'description',   // Optional description of the transaction
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'points_change' => 'integer',
    ];

    /**
     * Relationship: transaction belongs to a user (customer).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: transaction belongs to an employee (who processed it).
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
