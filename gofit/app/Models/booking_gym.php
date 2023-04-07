<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking_gym extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'member_id',
        'sesi_gym_id',
        'tgl_booking',
    ];
}
