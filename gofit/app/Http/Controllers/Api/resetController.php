<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\member;
use App\Models\instruktur;
use App\Models\pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class resetController extends Controller
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
   public function resetMemberExpired(Request $request){
    if(!self::cekKasir($request)){
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak punya akses',
            'data' => null,
        ], 401);
    }
    self::deactiveMember();
    self::deactiveDepositKelasPaketMember();
    return response()->json([
        'success' => true,
        'message' => 'Berhasil mereset data',
        'data' => null,
    ], 200);
   }
    //mendeactive membership yg kadarluasa
    public function deactiveMember(){
        $member = member::where('deactived_membership_at', '<', Carbon::now())
            ->get();
        foreach($member as $m){
            $m->deactived_membership_at = null;
            $m->save();
        }
        return;
    }
    //mendeactive deposit kelas paket yg kadarluasa
    public function deactiveDepositKelasPaketMember(){
        $member = member::where('deactived_deposit_kelas_paket', '<', Carbon::now())
            ->get();
        foreach($member as $m){
            $m->deposit_kelas_paket = 0;
            $m->deactived_deposit_kelas_paket = null;
            $m->kelas_deposit_kelas_paket_id = null;
            $m->save();
        }
    }
    //reset akumulasi terlambat instruktur per bulan
    public function resetAkumulasiTerlambat(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 401);
        }
        $instruktur = instruktur::all();
        foreach($instruktur as $i){
            $i->akumulasi_terlambat = 0;
            $i->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mereset data instruktur',
            'data' => null,
        ], 200);
    }
}
