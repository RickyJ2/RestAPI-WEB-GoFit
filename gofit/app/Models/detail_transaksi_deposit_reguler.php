<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail_transaksi_deposit_reguler extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'promo_id',
        'nominal',
    ];
}
