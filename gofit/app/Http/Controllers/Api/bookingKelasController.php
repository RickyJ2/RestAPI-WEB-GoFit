<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\member;
use App\Models\pegawai;
use App\Models\booking_kelas as bookingKelas;
use App\Models\jadwal_harian as jadwalHarian;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\kelas;
use Illuminate\Support\Facades\DB;

class bookingKelasController extends Controller
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
    //cek aktivasi member
    public function cekMemberActivation(Request $request){
        $member = member::find($request->user()->id);
        if(is_null($member) || $member->deactived_membership_at == null){
           return true;
       }else{
           return false;
       }
    }
    //total deposit reguler member dari booking kelas semua
    public function totalMemberDepositRegulerAll(Request $request, $member){
        $bookingKelasReguler = DB::table('booking_kelas')
                ->join('jadwal_harians', 'jadwal_harians.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umums', 'jadwal_umums.id', '=', 'jadwal_harians.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umums.kelas_id')
                ->where('member_id', $request->user()->id)
                ->where('no_nota', '=', null)
                ->where('is_cancelled', false)
                ->sum('kelas.harga');
        return $bookingKelasReguler;
    }
    //total deposit reguler member dari booking kelas
    public function totalMemberDepositRegulerNoKelasPaket(Request $request, $member){
        $bookingKelasReguler = DB::table('booking_kelas')
                ->join('jadwal_harians', 'jadwal_harians.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umums', 'jadwal_umums.id', '=', 'jadwal_harians.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umums.kelas_id')
                ->where('kelas.id', '!=', $member->kelas_deposit_kelas_paket_id)
                ->where('member_id', $request->user()->id)
                ->where('no_nota', '=', null)
                ->where('is_cancelled', false)
                ->sum('kelas.harga');
        return $bookingKelasReguler;
    }
    //total deposit kelas member dari booking kelas
    public function totalMemberDepositKelas(Request $request, $member){
        $bookingKelasPaket = DB::table('booking_kelas')
                ->join('jadwal_harians', 'jadwal_harians.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umums', 'jadwal_umums.id', '=', 'jadwal_harians.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umums.kelas_id')
                ->where('kelas.id', '=' ,$member->kelas_deposit_kelas_paket_id)
                ->where('member_id', $request->user()->id)
                ->where('no_nota', '=', null)
                ->where('is_cancelled', false)
                ->sum('kelas.harga');
        return $bookingKelasPaket;
    }
    //harga kelas dari deposit kelas paket member
    public function hargaKelasPaket(Request $request, $member){
        $hargaKelasPaket = DB::table('booking_kelas')
                ->join('jadwal_harians', 'jadwal_harians.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umums', 'jadwal_umums.id', '=', 'jadwal_harians.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umums.kelas_id')
                ->where('kelas.id', $member->kelas_deposit_kelas_paket_id)
                ->where('member_id', $request->user()->id)
                ->where('no_nota', '=', null)
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

            //harga kelas deposit kelas paket member
            $hargaKelasMember = kelas::find($member->kelas_deposit_kelas_paket_id)->harga;

            if($bookingKelasPaket >= ($member->deposit_kelas_paket * $hargaKelasMember)){
                $bookingKelasReguler += ($bookingKelasPaket - ($member->deposit_kelas_paket * $hargaKelasMember));
                if($member->deposit_reguler >= $bookingKelasReguler + $kelas->harga){
                    return false;
                }else{
                    return true;
                }
            }else{
                //kalau booking kelas paket belum melebihi deposit kelas paket
                if($kelas->id == $member->kelas_deposit_kelas_paket_id && ($member->deposit_kelas_paket * $hargaKelasMember) >= $bookingKelasPaket + $kelas->harga){
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
            ->where('is_cancelled', false)
            ->count();
        if($booking >= 10){
            return true;
        }else{
            return false;
        }
    }
    //cek sudah booking
    public function cekSelfBooking(Request $request){
        $booking = DB::table('booking_kelas')
            ->where('jadwal_harian_id', '=' ,$request->jadwal_harian_id)
            ->where('member_id', '=' ,$request->user()->id)
            ->where('is_cancelled', '=', false)
            ->first();
        if(is_null($booking)){
            return false;
        }else{
            return true;
        }
    }
    //index booking (hak akses kasir)
    public function index(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 401);
        }
        $bookingKelas = DB::table('booking_kelas')
                ->join('jadwal_harians', 'jadwal_harians.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umums', 'jadwal_umums.id', '=', 'jadwal_harians.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umums.kelas_id')
                ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
                ->join('members', 'members.id', '=', 'booking_kelas.member_id')
                ->leftJoin('status_jadwal_harians', 'jadwal_harians.status_id', '=', 'status_jadwal_harians.id')
                ->leftjoin('jenis_transaksis', 'jenis_transaksis.id',  '=', 'booking_kelas.jenis_pembayaran_id')
                ->leftJoin('izin_instrukturs', function ($join) {
                    $join->on('jadwal_umums.id', '=', 'izin_instrukturs.jadwal_umum_id')
                        ->on('jadwal_harians.tanggal', '=', 'izin_instrukturs.tanggal_izin')
                        ->where('izin_instrukturs.is_confirmed', 2);
                })
                ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs_penganti.id')
                ->select('booking_kelas.*', 'jadwal_harians.tanggal','members.nama as nama_member', 'jenis_transaksis.nama as jenis_pembayaran' ,DB::raw("TIME_FORMAT(jadwal_umums.jam_mulai, '%H:%i') as jam_mulai"),'jadwal_umums.hari', 'kelas.nama as nama_kelas','kelas.harga as harga_kelas' ,'instrukturs.nama as nama_instruktur', DB::raw('IFNULL(status_jadwal_harians.jenis_status, "") as jenis_status'), DB::raw('IFNULL(instrukturs_penganti.nama, "") as instruktur_penganti'))
                ->orderBy('booking_kelas.created_at', 'desc')
                ->get();
        return response()->json([
            'success' => true,
            'message' => 'List Semua Booking Kelas',
            'data'    => $bookingKelas  
        ], 200);
    }

    //Daftar Booking Jadwal Harian
    public function getListMember(Request $request){
        $bookingKelas = DB::table('booking_kelas')
                ->join('members', 'members.id', '=', 'booking_kelas.member_id')
                ->where('booking_kelas.jadwal_harian_id', $request->jadwal_harian_id)
                ->select('booking_kelas.*', 'members.nama as nama_member')
                ->orderBy('members.nama', 'desc')
                ->get();
        return response()->json([
            'success' => true,
            'message' => 'List Semua Member Booking Kelas',
            'data'    => $bookingKelas  
        ], 200);
    }

    //index booking member 
    public function show(Request $request){
        $bookingKelas = DB::table('booking_kelas')
                ->join('jadwal_harians', 'jadwal_harians.id', '=', 'booking_kelas.jadwal_harian_id')
                ->join('jadwal_umums', 'jadwal_umums.id', '=', 'jadwal_harians.jadwal_umum_id')
                ->join('kelas', 'kelas.id', '=', 'jadwal_umums.kelas_id')
                ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
                ->leftJoin('status_jadwal_harians', 'jadwal_harians.status_id', '=', 'status_jadwal_harians.id')
                ->leftJoin('izin_instrukturs', function ($join) {
                    $join->on('jadwal_umums.id', '=', 'izin_instrukturs.jadwal_umum_id')
                        ->on('jadwal_harians.tanggal', '=', 'izin_instrukturs.tanggal_izin')
                        ->where('izin_instrukturs.is_confirmed', 2);
                })
                ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs_penganti.id')
                ->where('member_id', $request->user()->id)
                ->select('booking_kelas.*', 'jadwal_harians.tanggal', DB::raw("TIME_FORMAT(jadwal_umums.jam_mulai, '%H:%i') as jam_mulai"),'jadwal_umums.hari', 'kelas.nama as nama_kelas','kelas.harga as harga_kelas' ,'instrukturs.nama as nama_instruktur', DB::raw('IFNULL(status_jadwal_harians.jenis_status, "") as jenis_status'), DB::raw('IFNULL(instrukturs_penganti.nama, "") as instruktur_penganti'))
                ->orderBy('booking_kelas.created_at', 'desc')
                ->get();
        return response()->json([
            'success' => true,
            'message' => 'List booking kelas member',
            'data' => $bookingKelas
        ], 200);
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
                'message' => "Jadwal Harian tidak boleh kosong",
                'data' => $validator->errors()
            ], 400);
        }
        if(self::cekSelfBooking($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah membooking kelas ini',
                'data' => self::cekSelfBooking($request)
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
