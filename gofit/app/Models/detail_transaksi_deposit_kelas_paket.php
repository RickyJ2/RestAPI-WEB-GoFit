<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail_transaksi_deposit_kelas_paket extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'promo_id',
        'kelas_id',
        'nominal',
        'total',
    ];
}
