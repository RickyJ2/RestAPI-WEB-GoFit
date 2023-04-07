<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class pegawai extends Authenticatable  
{
    use HasApiTokens, HasFactory;
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
