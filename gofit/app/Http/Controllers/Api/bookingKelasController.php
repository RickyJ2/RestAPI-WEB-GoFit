<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\member;
use App\Models\booking_kelas as bookingKelas;
use App\Models\jadwal_harian as jadwalHarian;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\kelas;

class bookingKelasController extends Controller
{
    //cek aktivasi member
    public function cekMemberActivation(Request $request){
        $member = member::find($request->user()->id);
        if(is_null($member) || $member->deactived_membership_at == null){
           return false;
       }else{
           return true;
       }
    }
    //total deposit reguler member dari booking kelas semua
    public function totalMemberDepositRegulerAll(Request $request, $member){
        $bookingKelasReguler = bookingKelas::join('jadwal_harian', 'jadwal_harian.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umum', 'jadwal_umum.id', '=', 'jadwal_harian.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umum.kelas_id')
                ->where('member_id', $request->user()->id)
                ->where('is_cancelled', false)
                ->sum('kelas.harga');
        return $bookingKelasReguler;
    }
    //total deposit reguler member dari booking kelas
    public function totalMemberDepositRegulerNoKelasPaket(Request $request, $member){
        $bookingKelasReguler = bookingKelas::join('jadwal_harian', 'jadwal_harian.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umum', 'jadwal_umum.id', '=', 'jadwal_harian.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umum.kelas_id')
                ->where('kelas.id', '!=', $member->kelas_deposit_kelas_paket_id)
                ->where('member_id', $request->user()->id)
                ->where('is_cancelled', false)
                ->sum('kelas.harga');
        return $bookingKelasReguler;
    }
    //total deposit kelas member dari booking kelas
    public function totalMemberDepositKelas(Request $request, $member){
        $bookingKelasPaket = bookingKelas::join('jadwal_harian', 'jadwal_harian.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umum', 'jadwal_umum.id', '=', 'jadwal_harian.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umum.kelas_id')
                ->where('kelas.id', $member->kelas_deposit_kelas_paket_id)
                ->where('member_id', $request->user()->id)
                ->where('is_cancelled', false)
                ->sum('kelas.harga');
        return $bookingKelasPaket;
    }
    //harga kelas dari deposit kelas paket member
    public function hargaKelasPaket(Request $request, $member){
        $hargaKelasPaket = bookingKelas::join('jadwal_harian', 'jadwal_harian.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umum', 'jadwal_umum.id', '=', 'jadwal_harian.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umum.kelas_id')
                ->where('kelas.id', $member->kelas_deposit_kelas_paket_id)
                ->where('member_id', $request->user()->id)
                ->where('is_cancelled', false)
                ->sum('kelas.harga');
        return $hargaKelasPaket;
    }
    //cek deposit member
    public function cekMemberDeposit(Request $request){
        $member = member::find($request->user()->id);
        $jadwalHarian = jadwalHarian::find($request->jadwal_harian_id);
        $jadwalUmum = jadwalUmum::find($jadwalHarian->jadwal_umum_id);
        $kelas = kelas::find($jadwalUmum->kelas_id);
        
        if($member->kelas_deposit_kelas_paket_id != null){
            //kalau member punya deposit kelas paket
            $bookingKelasReguler = self::totalMemberDepositRegulerNoKelasPaket($request, $member);
            $bookingKelasPaket = self::totalMemberDepositKelas($request, $member);
            if($bookingKelasPaket >= $member->deposit_kelas_paket){
                //kalau booking kelas paket sudah melebihi deposit kelas paket
                $hargaKelasPaket = self::hargaKelasPaket($request, $member);
                $bookingKelasReguler += ($hargaKelasPaket * ($bookingKelasPaket - $member->deposit_kelas_paket));
                if($member->deposit_reguler >= $bookingKelasReguler + $kelas->harga){
                    return false;
                }else{
                    return true;
                }
            }else{
                //kalau booking kelas paket belum melebihi deposit kelas paket
                if($kelas->id == $member->kelas_deposit_kelas_paket_id && $member->deposit_kelas_paket >= $bookingKelasPaket + 1){
                    //kalau kelas yang di booking sekarang sama dengan kelas deposit kelas paket
                    return false;
                }else if($kelas->id != $member->kelas_deposit_kelas_paket_id && $member->deposit_reguler >= $bookingKelasReguler + $kelas->harga){
                    //kalau kelas yang di booking sekarang tidak sama dengan kelas deposit kelas paket
                    return false;
                }else{
                    return true;
                }
            }
        }else{
            //kalau member tidak punya deposit kelas paket
            $bookingKelasReguler = self::totalMemberDepositRegulerAll($request, $member);
            if($member->deposit_reguler >= $bookingKelasReguler + $kelas->harga){
                return false;
            }else{
                return true;
            }
        }
    }
    //cek kuota
    public function cekKuota(Request $request){
        $booking = bookingKelas::where('jadwal_harian_id', $request->jadwal_harian_id)
            ->where('tgl_booking', $request->tgl_booking)
            ->where('is_cancelled', false)
            ->count();
        if($booking >= 10){
            return true;
        }else{
            return false;
        }
    }
    //tambah booking
    public function add(Request $request){
        if(self::cekMemberActivation($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses atau belum mengaktifkan membership',
                'data' => null
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'jadwal_harian_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        if(self::cekMemberDeposit($request)){
            return response()->json([
                'success' => false,
                'message' => 'Deposit anda tidak mencukupi, batalkan booking kelas lainnya atau tambah deposit anda!',
                'data' => null
            ], 400);
        }
        if(self::cekKuota($request)){
            return response()->json([
                'success' => false,
                'message' => 'Kuota sesi kelas sudah penuh',
                'data' => null
            ], 400);
        }
        $booking = new bookingKelas;
        $booking->member_id = $request->user()->id;
        $booking->jadwal_harian_id = $request->jadwal_harian_id;
        if($booking->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil booking kelas',
                'data' => $booking
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal booking kelas',
                'data' => null
            ], 400);
        }
    }
    //batal booking
    public function cancel(Request $request){
        $booking = bookingKelas::find($request->id);
        if(is_null($booking)){
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
                'data' => null
            ], 400);
        }
        if($booking->is_cancelled){
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah dibatalkan',
                'data' => null
            ], 400);
        }
        $jadwalHarian = jadwalHarian::find($booking->jadwal_harian_id);
        if(Carbon::now()->format('Y-m-d') >= $jadwalHarian->tanggal){
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah tidak bisa dibatalkan',
                'data' => null
            ], 400);
        }
        $booking->is_cancelled = true;
        if($booking->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membatalkan booking',
                'data' => $booking
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan booking',
                'data' => null
            ], 400);
        }
    }
}
