<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class instruktur extends Authenticatable
{
    use HasApiTokens,HasFactory,SoftDeletes;
    protected $fillable =[
        'nama',
        'alamat',
        'tgl_lahir',
        'no_telp',
        'username',
        'password'
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected $dates = ['deleted_at'];
}
