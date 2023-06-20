<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    protected $fillable = [
        'id_user',
        'id_seminar',
        'stars',
        'comment'
    ];
}