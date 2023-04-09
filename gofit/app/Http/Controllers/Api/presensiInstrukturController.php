<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\presensi_instruktur as presensiInstruktur;
use App\Models\jadwal_harian as jadwalHarian;
use App\Models\izin_instruktur as izinInstruktur;
use App\Models\pegawai;

class presensiInstrukturController extends Controller
{
    //cek apakah Manajer Operasional
    public function cekManajerOperasional(Request $request){
        $user = pegawai::where('id', $request->user()->id)->first();
        if(is_null($user) || $user->jabatan_id != 1){
           return false;
       }else{
           return true;
       }
    }
    //update jam mulai kelas
    public function updateJamMulai(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'jadwal_harian_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $presensiInstruktur = new presensiInstruktur;
        $presensiInstruktur->jadwal_harian_id = $request->jadwal_harian_id;

        $jadwalHarian = jadwalHarian::find($request->jadwal_harian_id);
        if($jadwalHarian->status_id == 2){
            $izinInstruktur = izinInstruktur::where('jadwal_umum_id', $jadwalHarian->jadwal_umum_id)
                ->where('tanggal', $jadwalHarian->tanggal)
                ->first();
            $presensiInstruktur->instruktur_id = $izinInstruktur->instruktur_penganti_id;
        }else{
            $presensiInstruktur->instruktur_id = $jadwalHarian->jadwalUmum->instruktur_id;
        }

        $presensiInstruktur->jenis_presensi = 'masuk';
        if($presensiInstruktur->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil',
                'data' => $presensiInstruktur
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
                'data' => null
            ], 400);
        }
    }
    //update jam selesai kelas
    public function updateJamSelesai(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'jadwal_harian_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $presensiInstruktur = new presensiInstruktur;
        $presensiInstruktur->jadwal_harian_id = $request->jadwal_harian_id;

        $jadwalHarian = jadwalHarian::find($request->jadwal_harian_id);
        if($jadwalHarian->status_id == 2){
            $izinInstruktur = izinInstruktur::where('jadwal_umum_id', $jadwalHarian->jadwal_umum_id)
                ->where('tanggal', $jadwalHarian->tanggal)
                ->first();
            $presensiInstruktur->instruktur_id = $izinInstruktur->instruktur_penganti_id;
        }else{
            $presensiInstruktur->instruktur_id = $jadwalHarian->jadwalUmum->instruktur_id;
        }

        $presensiInstruktur->jenis_presensi = 'selesai';
        if($presensiInstruktur->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil',
                'data' => $presensiInstruktur
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal',
                'data' => null
            ], 400);
        }
    }
}
