<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\transaksi;
use App\Models\detail_transaksi_deposit_reguler as detailTransaksiDepositReguler;
use App\Models\detail_transaksi_deposit_kelas_paket as detailTransaksiDepositKelasPaket;
use App\Models\pegawai;
use App\Models\member;
use App\Models\promo;
use App\Models\booking_gym as bookingGym;
use App\Models\booking_kelas as bookingKelas;
use App\Models\jadwal_harian as jadwalHarian;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\kelas;

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
        
        $transaksi = Transaksi::where('pegawai_id', $request->user()->id)
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
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
            'jenis_transaksi_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
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
                'data' => [
                    'no_nota' => $transaksi->id,
                    'member_id' => $member->id,
                    'nama_member' => $member->nama,
                    'masa_aktif_member' => Carbon::parse($member->deactived_membership_at)->format('d/m/Y'),
                    'created_at' => Carbon::parse($transaksi->created_at)->format('d/m/Y H:i'),
                ],
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
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
            'jenis_transaksi_id' => 'required',
            'nominal' => 'required|integer|min:500000',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }

        $transaksi = self::createTransaksi($request);
        if(is_null($transaksi)){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi',
                'data' => null
            ], 400);
        }
        $detailTransaksi = new detailTransaksiDepositReguler;
        $detailTransaksi->no_nota = $transaksi->id;       
        $detailTransaksi->nominal = $request->nominal;

        //update deposit member
        $member = member::find($request->member_id);
        $sisa_deposit = $member->deposit_reguler; //simpan untuk dikembalikan ke client
        $member->deposit_reguler += $request->nominal;

        if(isset($request->promo_id)){
            $detailTransaksi->promo_id = $request->promo_id;
            $promo = promo::find($request->promo_id);
            $member->deposit_reguler += $promo->bonus;
        }
         
        if($detailTransaksi->save() && $member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat transaksi deposit reguler',
                'data' => ['no_nota' => $transaksi->id,
                    'member_id' => $member->id,
                    'nama_member' => $member->nama,
                    'sisa_deposit' => $sisa_deposit,
                    'total_deposit' => $member->deposit_reguler,
                    'created_at' => Carbon::parse($transaksi->created_at)->format('d/m/Y H:i'),
                    ],
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
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
            'jenis_transaksi_id' => 'required',
            'kelas_id' => 'required|integer',
            'nominal' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        if(self::cekKelasPaketMember($request->member_id)){
            return response()->json([
                'success' => false,
                'message' => 'Member sudah memiliki kelas paket',
                'data' => null
            ], 402);
        }
        $transaksi = self::createTransaksi($request);
        if(is_null($transaksi)){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi deposit kelas paket',
                'data' => null
            ], 400);
        }
        $kelas = Kelas::find($request->kelas_id);
        $detailTransaksi = new detailTransaksiDepositKelasPaket;
        $detailTransaksi->no_nota = $transaksi->id;
        $detailTransaksi->kelas_id = $request->kelas_id;
        $detailTransaksi->nominal = $request->nominal;
        $detailTransaksi->total = $request->nominal * $kelas->harga;

        //update data member
        $member = member::find($request->member_id);
        $member->deposit_kelas_paket += $request->nominal;
        $member->kelas_deposit_kelas_paket_id = $request->kelas_id;
        if($request->nominal < 10){
            $member->deactived_deposit_kelas_paket = Carbon::now()->addMonth();
        }else{
            $member->deactived_deposit_kelas_paket = Carbon::now()->addMonths(2);
        }

        if(isset($request->promo_id)){
            $detailTransaksi->promo_id = $request->promo_id;
            $promo = promo::find($request->promo_id);
            $member->deposit_kelas_paket += $promo->bonus;
        }

        if($detailTransaksi->save() && $member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat transaksi deposit kelas paket',
                'data' => [$transaksi,
                    'no_nota' => $transaksi->id,
                    'member_id' => $member->id,
                    'nama_member' => $member->nama,
                    'total_deposit' => $member->deposit_kelas_paket,
                    'created_at' => Carbon::parse($transaksi->created_at)->format('d/m/Y H:i'),
                    'masa_aktif_deposit_kelas_paket' => Carbon::parse($member->deactived_deposit_kelas_paket)->format('d/m/Y'),
                ],
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi deposit kelas paket',
                'data' => null
            ], 400);
        }
    }

    //Update presensi Booking Gym
    public function updatePresent(Request $request){
        $booking = bookingGym::find($request->id);
        if(is_null($booking)){
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
                'data' => null
            ], 400);
        }

        $booking->present_at = Carbon::now();
        $transaksi = self::createTransaksi($request);
        if(is_null($transaksi)){
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi',
                'data' => null
            ], 400);
        }
        $booking->no_nota = $transaksi->id;         
        $booking->save();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mempresensi booking',
            'data' => $booking
        ], 200);
    }

    //Update presensi Booking Kelas
    public function updatePresentKelas(Request $request){
        $requestData = $request->all();
        foreach ($requestData as $key => $value) {
            $bookingKelas = bookingKelas::find($key);
            $member = member::find($bookingKelas->member_id);
            
            if($value == '1' && is_null($bookingKelas->present_at)){
                $bookingKelas->present_at = Carbon::now();
                $bookingKelas->save();
            }
            if(is_null($bookingKelas->no_nota)){
                $transaksi = new Transaksi;
                $transaksi->member_id = $member->id;
                $transaksi->jenis_transaksi_id = 5;
                $transaksi->save();
                
                $transaksi = Transaksi::where('member_id', $member->id)
                    ->where('jenis_transaksi_id', 5)
                    ->where('created_at', $transaksi->created_at)
                    ->where('updated_at', $transaksi->updated_at)
                    ->first();
                if(is_null($transaksi)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat transaksi',
                        'data' => null
                    ], 400);
                }
                $bookingKelas->no_nota = $transaksi->id;
                $bookingKelas->save();

                //Motong deposit Member
                $jadwalHarian = jadwalHarian::find($bookingKelas->jadwal_harian_id);
                $jadwalUmum = jadwalUmum::find($jadwalHarian->jadwal_umum_id);
                $kelas = kelas::find($jadwalUmum->kelas_id);
                //kalau deposit kelas memmber sama dengan kelas
                if($member->kelas_deposit_kelas_paket_id == $kelas->id && $member->deposit_kelas_paket > 0){
                    $member->deposit_kelas_paket = $member->deposit_kelas_paket - 1;
                    $member->save();
                    //update utk struk
                    $bookingKelas->jenis_pembayaran_id = 3;
                    $bookingKelas->sisa_deposit = $member->deposit_kelas_paket;
                    $bookingKelas->masa_berlaku_deposit = $member->deactived_deposit_kelas_paket;
                    $bookingKelas->save();
            
                }else{
                    $member->deposit_reguler -= $kelas->harga;
                    $member->save();
                    //update utk struk
                    $bookingKelas->jenis_pembayaran_id = 2;
                    $bookingKelas->sisa_deposit = $member->deposit_reguler;
                    $bookingKelas->save();
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mempresensi booking',
            'data' => null,
        ], 200);
    }
}
