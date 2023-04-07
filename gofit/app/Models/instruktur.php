<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class instruktur extends Authenticatable
{
    use HasApiTokens,HasFactory;
    protected $fillable =[
        'nama',
        'alamat',
        'tgl_lahir',
        'no_telp',
        'akumulasi_terlambat',
        'username',
        'password'
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];
}
