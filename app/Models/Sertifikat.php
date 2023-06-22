<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';
    protected $fillable = [
        'id_user',
        'id_seminar',
        'kode_sertifikat',
    ];
}