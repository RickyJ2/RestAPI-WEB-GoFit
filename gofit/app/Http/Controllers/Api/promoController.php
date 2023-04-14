<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\promo;

class promoController extends Controller
{
    //tampilkan promo
    public function index(){
        $promo = promo::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Promo',
            'data' => $promo,
        ], 200);
    }
}
