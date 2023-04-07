<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class instruktur extends Model
{
    use HasFactory;
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
