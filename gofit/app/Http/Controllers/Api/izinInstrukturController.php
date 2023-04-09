<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\pegawai;
use App\Models\instruktur;
use App\Models\izin_instruktur as izinInstruktur;

class izinInstrukturController extends Controller
{
    //cek apakah instruktur ada jadwal yg tabrak
    public function cekJadwalInstruktur(Request $request){
        $jadwalUmum = DB::table('jadwal_umums')
            ->where('instruktur_id', $request->instruktur_penganti_id)
            ->where('hari', Carbon::parse($request->tanggal_izin)->format('l'))
            ->where('jam_mulai', '>' ,Carbon::parse($request->jam_mulai)->subHours(2)->format('H:i'))
            ->where('jam_mulai', '<' ,Carbon::parse($request->jam_mulai)->addHours(2)->format('H:i'))
            ->first();
        $izinInstruktur = DB::table('izin_instrukturs')
            ->leftJoin('jadwal_umums', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->where('izin_instrukturs.instruktur_penganti_id', $request->instruktur_penganti_id)
            ->where('jadwal_umums.hari', Carbon::parse($request->tanggal_izin)->format('l'))
            ->where('jadwal_umums.jam_mulai', '>' ,Carbon::parse($request->jam_mulai)->subHours(2)->format('H:i'))
            ->where('jadwal_umums.jam_mulai', '<' ,Carbon::parse($request->jam_mulai)->addHours(2)->format('H:i'))
            ->first();
        if(!is_null($jadwalUmum) || !is_null($izinInstruktur)){
            return true;
        }else{
            return false;
        }
    }
    //cek apakah Manajer Operasional
    public function cekManajerOperasional(Request $request){
        $user = pegawai::where('id', $request->user()->id)->first();
        if(is_null($user) || $user->jabatan_id != 1){
           return false;
       }else{
           return true;
       }
    }
    //tampilkan daftar izin (MO)
    public function index(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $izinInstruktur = DB::table('izin_instrukturs')
            ->leftJoin('jadwal_umums', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->leftJoin('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs.id')
            ->leftJoin('instrukturs as instrukturs_pengaju', 'izin_instrukturs.instruktur_pengaju_id', '=', 'instrukturs_pengaju.id')
            ->select('kelas.nama', 'instrukturs_penganti.nama', 'instrukturs_pengaju.nama','izin_instruturs.tanggal_izin', 'izin_instrukturs.is_confirmed')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar izin instruktur',
            'data' => $izinInstruktur
        ], 200);
    }
    //tampilkan daftar izin yg blm diverif (MO)
    public function indexFilterNoVerif(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $izinInstruktur = DB::table('izin_instrukturs')
            ->leftJoin('jadwal_umums', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->leftJoin('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs.id')
            ->leftJoin('instrukturs as instrukturs_pengaju', 'izin_instrukturs.instruktur_pengaju_id', '=', 'instrukturs_pengaju.id')
            ->select('kelas.nama', 'instrukturs_penganti.nama', 'instrukturs_pengaju.nama','izin_instruturs.tanggal_izin', 'izin_instrukturs.is_confirmed')
            ->where('izin_instrukturs.is_confirmed', false)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar izin instruktur belum diverif',
            'data' => $izinInstruktur
        ], 200);
    }
    //Konfimasi izin instruktur (MO)
    public function updateVerifIzin(Request $request, $id){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $izinInstruktur = izinInstruktur::find($id);
        if(is_null($izinInstruktur)){
            return response()->json([
                'success' => false,
                'message' => 'Izin Instruktur tidak ditemukan',
                'data' => null
            ], 400);
        }
        $izinInstruktur->is_confirmed = true;
        if($izinInstruktur->save()){
            return response()->json([
                'success' => true,
                'message' => 'Izin Instruktur berhasil diverif',
                'data' => $izinInstruktur
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Izin Instruktur gagal diverif',
                'data' => null
            ], 400);
        }
    }
    //tambahkan izin instruktur (Instruktur)
    public function add(Request $request){
        $instruktur = instruktur::find($request->user()->id);
        if(is_null($instruktur)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $Validator = Validator::make($request->all(), [
            'jadwal_umum_id' => 'required',
            'instruktur_penganti_id' => 'required',
            'tanggal_izin' => 'required'
        ]);
        if($Validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap',
                'data' => null
            ], 400);
        }
        if(self::cekJadwalInstruktur($request)){
            return response()->json([
                'success' => false,
                'message' => 'Jadwal sudah ada',
                'data' => null
            ], 400);
        }
        $izinInstruktur = new izinInstruktur();
        $izinInstruktur->jadwal_umum_id = $request->jadwal_umum_id;
        $izinInstruktur->instruktur_penganti_id = $request->instruktur_penganti_id;
        $izinInstruktur->instruktur_pengaju_id = $request->user()->id;
        $izinInstruktur->tanggal_izin = $request->tanggal_izin;
        if($izinInstruktur->save()){
            return response()->json([
                'success' => true,
                'message' => 'Izin Instruktur berhasil ditambahkan',
                'data' => $izinInstruktur
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Izin Instruktur gagal ditambahkan',
                'data' => null
            ], 400);
        }
    }
    //tampilkan daftar izin (instruktur)
    public function show(Request $request){
        $instruktur = instruktur::find($request->user()->id);
        if(is_null($instruktur)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $izinInstruktur = DB::table('izin_instrukturs')
            ->leftJoin('jadwal_umums', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->leftJoin('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs.id')
            ->leftJoin('instrukturs as instrukturs_pengaju', 'izin_instrukturs.instruktur_pengaju_id', '=', 'instrukturs_pengaju.id')
            ->select('kelas.nama', 'instrukturs_penganti.nama', 'instrukturs_pengaju.nama','izin_instruturs.tanggal_izin', 'izin_instrukturs.is_confirmed')
            ->where('izin_instrukturs.instruktur_pengaju_id', $request->user()->id)
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar izin instruktur',
            'data' => $izinInstruktur
        ], 200);
    }
}
