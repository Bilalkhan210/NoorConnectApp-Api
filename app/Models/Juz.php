<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Juz extends Model
{
    protected $fillable = ['juz_number', 'data'];

    
    protected $casts = [
        'data' => 'array',
    ];
}