<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class member extends Authenticatable
{
    use HasApiTokens,HasFactory, SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'alamat',
        'tgl_lahir',
        'no_telp',
        'email',
        'password',
        'deactived_membership_at',
        'deposit_reguler',
        'deposit_kelas_paket',
        'deactived_deposit_kelas_paket',
        'kelas_deposit_kelas_paket_id',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected $dates = ['deleted_at'];
}
