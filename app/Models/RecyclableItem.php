<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecyclableItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These are the fields you can create/update via the controller.
     */
    protected $fillable = [
        'name',
        'desc',
        'img',
    ];
}
