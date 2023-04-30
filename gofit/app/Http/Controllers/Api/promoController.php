<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class promoController extends Controller
{
    //tampilkan promo
    public function index(){
        $promo = DB::table('promos')
            ->leftJoin('jenis_transaksis', 'promos.jenis_promo_id', '=', 'jenis_transaksis.id')
            ->select('promos.*', 'jenis_transaksis.nama as jenis_promo')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Promo',
            'data' => $promo,
        ], 200);
    }
}
