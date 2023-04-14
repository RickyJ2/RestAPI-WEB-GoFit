<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\pegawai;
use App\Models\instruktur;
use App\Models\member;
use App\Models\booking_kelas;
use App\Models\transaksi;
use App\Models\presensi_instruktur as PresensiInstruktur;

class presensiKelasController extends Controller
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
   //cek apakah instrutur
   public function cekInstruktur(Request $request){
       $user = instruktur::where('id', $request->user()->id)->first();
       if(is_null($user)){
           return false;
       }else{
           return true;
       }
   }
   //cek apakah sudah presensi
    public function cekPresensi($id){
        $presensiInstruktur = PresensiInstruktur::where('jadwal_harian_id', $id)->first();
        if(is_null($presensiInstruktur)){
            return true;
        }else{
            return false;
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
        $booking = booking_kelas::where('tgl_booking', Carbon::now()->format('Y-m-d'))
            ->where('is_cancelled', false)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Booking Hari Ini',
            'data' => $booking,
        ], 200);
    }
    //tampilkan daftar booking jadwal harian hari ini
    public function showBookingJadwalHarian(Request $request, $id){
        if(!self::cekInstruktur($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 400);
        }
        if(self::cekPresensi($id)){
            return response()->json([
                'success' => false,
                'message' => 'Instruktur harus presensi terlebih dahulu!',
                'data' => null,
            ], 400);
        }
        $booking = booking_kelas::where('jadwal_harian_id', $id)
            ->where('is_cancelled', false)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Booking Jadwal Harian',
            'data' => $booking,
        ], 200);
    }
    //update presensi kelas member
    
}
