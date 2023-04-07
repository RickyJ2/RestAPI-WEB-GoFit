<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'alamat',
        'tgl_lahir',
        'no_telp',
        'email',
        'username',
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

}
