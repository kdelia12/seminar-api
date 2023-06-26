<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    protected $fillable = [
        'name',
        'short_description',
        'full_description',
        'participants',
        'date_and_time',
        'quota',
        'participant_count',
        'speaker',
        'category',
        'lokasi',
        'alamat'

    ];
}