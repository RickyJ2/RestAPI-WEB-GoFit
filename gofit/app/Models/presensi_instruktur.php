<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class presensi_instruktur extends Model
{
    use HasFactory;
    protected $fillable = [
        'jadwal_harian_id',
        'instruktur_id',
        'jenis_presensi',
    ];
}
