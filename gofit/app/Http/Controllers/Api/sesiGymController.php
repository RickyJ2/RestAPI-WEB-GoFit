<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\sesi_gym as sesiGym;

class sesiGymController extends Controller
{
    //index
    public function index()
    {
        // DB::raw("TIME_FORMAT(jadwal_umums.jam_mulai, '%H:%i') as jam_mulai")
        $sesiGym = DB::table('sesi_gyms') 
            -> select('sesi_gyms.id', DB::raw("TIME_FORMAT(sesi_gyms.jam_mulai, '%H:%i') as jam_mulai"), DB::raw("TIME_FORMAT(sesi_gyms.jam_selesai, '%H:%i') as jam_selesai"))
            -> get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Sesi Gym',
            'data' => $sesiGym,
        ], 200);
    }
}
