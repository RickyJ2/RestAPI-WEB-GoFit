<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sesi_gym extends Model
{
    use HasFactory;
    protected $fillable = [
        'jam_mulai',
        'jam_selesai',
    ];
}
