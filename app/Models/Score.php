<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['name', 'score', 'average_time', 'answers'];
    protected $casts = [
        'answers' => 'array',
    ];
}
