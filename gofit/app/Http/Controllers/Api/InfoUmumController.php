<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\informasi_umum as InfoUmum;

class InfoUmumController extends Controller
{
    public function show(){
        $infoUmum = InfoUmum::all()->first();
        return response()->json([
            'success' => true,
            'message' => 'Informasi Umum Gym GoFit',
            'data' => $infoUmum
        ], 200);
    }
}
