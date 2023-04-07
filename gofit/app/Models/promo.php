<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promo extends Model
{
    use HasFactory;
    protected $fillable = [
        'jenis_promo_id',
        'kriteria_pembelian',
        'bonus',
        'masa_berlaku',
        'is_active',
    ];
}
