<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal_harian extends Model
{
    use HasFactory;
    protected $fillable = [
        'jadwal_umum_id',
        'tanggal',
        'status_id',
    ];
}
