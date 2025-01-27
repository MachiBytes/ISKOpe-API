<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'category', 
        'date_status',
        'time_status',
        'place_last_seen',
        'image_link'
    ];
}
