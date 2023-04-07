<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pegawai extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'alamat',
        'tgl_lahir',
        'no_telp',
        'jabatan_id',
        'username',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];
}
