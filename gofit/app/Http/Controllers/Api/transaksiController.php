<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\transaksi;
use App\Models\detail_transaksi_deposit_reguler as detailTransaksiDepositReguler;
use App\Models\detail_transaksi_deposit_kelas_paket as detailTransaksiDepositKelasPaket;
use App\Models\pegawai;
use App\Models\member;

class transaksiController extends Controller
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
   //cek deposit kelas paket member
   public function cekKelasPaketMember($id){
        $member = member::find($id);
        if(is_null($member->deactived_deposit_kelas_paket)){
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
    //create transaksi aktivasi
    public function activationTransaksi(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|integer',
            'jenis_transaksi_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi',
                'data' => null
            ], 400);
        }
        $transaksi = self::createTransaksi($request);
        if(is_null($transaksi)){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi aktivasi',
                'data' => null
            ], 400);
        }
        //menambah masa aktif member
        $member = member::find($request->member_id);
        if(is_null($member->deactived_membership_at)){
            $member->deactived_membership_at = Carbon::now()->addYear();
        }else{
            $member->deactived_membership_at = Carbon::parse($member->deactived_membership_at)->addYear();
        }
        if($member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat transaksi aktivasi',
                'data' => $transaksi,
                'masa aktif member' => $member->deactived_membership_at
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Berhasil membuat transaksi, tapi gagal menambah masa aktif member',
                'data' => $transaksi,       
            ], 400);
        }
    }
    //create transaksi deposit reguler
    public function depositRegulerTransaksi(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'pegawai_id' => 'required|integer',
            'member_id' => 'required|integer',
            'jenis_transaksi_id' => 'required',
            'nominal' => 'required|integer|min:500000',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi',
                'data' => null
            ], 400);
        }

        $transaksi = self::createTransaksi($request);
        if(is_null($transaksi)){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi deposit reguler',
                'data' => null
            ], 400);
        }
        $detailTransaksi = new detailTransaksiDepositReguler;
        $detailTransaksi->no_nota = $transaksi->id;
        $detailTransaksi->promo_id = $request->promo_id;
        $detailTransaksi->nominal = $request->nominal;
        //update deposit member
        $member = member::find($request->member_id);
        $member->deposit_reguler += $request->total;
         
        if($detailTransaksi->save() && $member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat transaksi deposit reguler',
                'data' => $transaksi
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi deposit reguler',
                'data' => null
            ], 400);
        }
    }
    //create transaksi deposit kelas paket
    public function depositKelasPaketTransaksi(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'pegawai_id' => 'required|integer',
            'member_id' => 'required|integer',
            'jenis_transaksi_id' => 'required',
            'kelas_id' => 'required|integer',
            'nominal' => 'required|integer',
            'total' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi',
                'data' => null
            ], 400);
        }
        if(self::cekKelasPaketMember($request->member_id)){
            return response()->json([
                'success' => false,
                'message' => 'Member sudah memiliki kelas paket',
                'data' => null
            ], 400);
        }
        $transaksi = self::createTransaksi($request);
        if(is_null($transaksi)){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi deposit kelas paket',
                'data' => null
            ], 400);
        }
        $detailTransaksi = new detailTransaksiDepositKelasPaket;
        $detailTransaksi->no_nota = $transaksi->id;
        $detailTransaksi->kelas_id = $request->kelas_id;
        $detailTransaksi->promo_id = $request->promo_id;
        $detailTransaksi->nominal = $request->nominal;
        $detailTransaksi->total = $request->total;

        //update data member
        $member = member::find($request->member_id);
        $member->deposit_kelas_paket += $request->total;
        $member->kelas_deposit_kelas_paket_id = $request->kelas_id;
        if($request->nominal < 10){
            $member->deactived_deposit_kelas_paket = Carbon::now()->addMonth();
        }else{
            $member->deactived_deposit_kelas_paket = Carbon::now()->addMonths(2);
        }

        if($detailTransaksi->save() && $member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat transaksi deposit kelas paket',
                'data' => $transaksi,
                'masa aktif deposit kelas paket' => $member->deactived_deposit_kelas_paket
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi deposit kelas paket',
                'data' => null
            ], 400);
        }
    }
}
