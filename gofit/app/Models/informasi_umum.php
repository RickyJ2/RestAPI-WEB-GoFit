<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class informasi_umum extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'alamat',
        'deskripsi',
        'biaya_aktivasi_membership',
        'min_deposit_reguler',
        'max_deposit_kelas_awal',
    ];
}
