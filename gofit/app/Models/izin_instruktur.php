<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class izin_instruktur extends Model
{
    use HasFactory;
    protected $fillable = [
        'jadwal_umum_id',
        'instruktur_pengaju_id',
        'instruktur_penganti_id',
        'tanggal_izin',
        'is_confirmed',
    ];
}
