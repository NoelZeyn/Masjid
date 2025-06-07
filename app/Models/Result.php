<?php
// app/Models/Result.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['name', 'score', 'average_time', 'answers'];

    protected $casts = [
        'answers' => 'array',
    ];
}
