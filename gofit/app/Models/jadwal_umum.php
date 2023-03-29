<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal_umum extends Model
{
    use HasFactory;
    protected $fillable = [
        'kelas_id',
        'instruktur_id',
        'hari',
        'jam_mulai',
    ];
}
