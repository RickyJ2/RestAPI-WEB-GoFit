<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\pegawai;
use App\Models\member;
use App\Models\booking_gym;
use App\Models\transaksi;

class presensiGymController extends Controller
{
    //cek apakah kasir
    public function cekKasir(Request $request){
        $user = pegawai::where('id', $request->user()->id)->first();
        if(is_null($user) || $user->jabatan_id != 3){
           return false;
       }else{
           return true;
       }
   }
   //create transaksi
    public function createTransaksi(Request $request){
        $transaksi = new Transaksi;
        $transaksi->pegawai_id = $request->user()->id;
        $transaksi->member_id = $request->member_id;
        $transaksi->jenis_transaksi_id = $request->jenis_transaksi_id;
        $transaksi->save();
        
        $transaksi = Transaksi::where('pegawai_id', $request->pegawai_id)
            ->where('member_id', $request->member_id)
            ->where('jenis_transaksi_id', $request->jenis_transaksi_id)
            ->where('created_at', $transaksi->created_at)
            ->where('updated_at', $transaksi->updated_at)
            ->first();
        return $transaksi;
    }
   //tampilkan daftar booking hari ini
    public function showToday(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 400);
        }
        $booking = booking_gym::where('tgl_booking', Carbon::now()->format('Y-m-d'))
            ->where('is_cancelled', false)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Booking Hari Ini',
            'data' => $booking,
        ], 200);
    }
    //presensi member gym
    public function presensiMember(Request $request, $id){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 400);
        }
        $booking = booking_gym::find($id);
        if(is_null($booking)){
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
                'data' => null,
            ], 400);
        }
        $transaksi = self::createTransaksi($request);
        $booking = booking_gym::find($id);
        $booking->no_nota = $transaksi->id;
        if($booking->save()){
            return response()->json([
                'success' => true,
                'message' => 'Presensi Berhasil',
                'data' => $booking,
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Presensi Gagal',
                'data' => null,
            ], 400);
        }
    }
}
