<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\pegawai;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\izin_instruktur as izinInstruktur;

class JadwalUmumController extends Controller
{
    //cek apakah jadwal instruktur sudah ada
    public function cekJadwalInstruktur(Request $request){
        $jadwalUmum = jadwalUmum::where('instruktur_id', $request->instruktur_id)
            ->where('hari', $request->hari)
            ->where('jam_mulai', '>' ,Carbon::parse($request->jam_mulai)->subHour()->format('H:i'))
            ->where('jam_mulai', '<' ,Carbon::parse($request->jam_mulai)->addHour()->format('H:i'))
            ->first();
        $izin_instruktur = izinInstruktur::where('instruktur_penganti_id', $request->instruktur_id)
            ->leftJoin('jadwal_umums', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')    
            ->where('jadwal_umums.hari', $request->hari)
            ->where('jadwal_umums.jam_mulai', '>' ,Carbon::parse($request->jam_mulai)->subHour()->format('H:i'))
            ->where('jadwal_umums.jam_mulai', '<' ,Carbon::parse($request->jam_mulai)->addHour()->format('H:i'))
            ->first();
        if(is_null($jadwalUmum) && is_null($izin_instruktur)){
            return false;
        }else{
            return true;
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
    //Tambah jadwal umum (Manajer Operasional)
    public function add(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'instruktur_id' => 'required|integer',
            'kelas_id' => 'required|integer',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        if(self::cekJadwalInstruktur($request)){
            return response()->json([
                'success' => false,
                'message' => [
                    'instruktur_id' => ['Instruktur sudah ada jadwalnya'],
                ],
                'data' => null
            ], 400);
        }
        $jadwalUmum = new jadwalUmum();
        $jadwalUmum->instruktur_id = $request->instruktur_id;
        $jadwalUmum->kelas_id = $request->kelas_id;
        $jadwalUmum->hari = $request->hari;
        $jadwalUmum->jam_mulai = $request->jam_mulai;
        if($jadwalUmum->save()){
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Umum berhasil ditambahkan',
                'data' => $jadwalUmum
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Umum gagal ditambahkan',
                'data' => null
            ], 400);
        }
    }
    //Ubah jadwal umum (Manajer Operasional)
    public function update(Request $request, $id){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'instruktur_id' => 'required|integer',
            'kelas_id' => 'required|integer',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        if(self::cekJadwalInstruktur($request)){
            return response()->json([
                'success' => false,
                'message' => [
                    'instruktur_id' => ['Instruktur sudah ada jadwalnya'],
                ],
                'data' => null
            ], 400);
        }
        $jadwalUmum = jadwalUmum::find($id);
        if(is_null($jadwalUmum)){
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Umum tidak ditemukan',
                'data' => null
            ], 400);
        }
        $jadwalUmum->instruktur_id = $request->instruktur_id;
        $jadwalUmum->kelas_id = $request->kelas_id;
        $jadwalUmum->hari = $request->hari;
        $jadwalUmum->jam_mulai = $request->jam_mulai;
        if($jadwalUmum->save()){
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Umum berhasil diubah',
                'data' => $jadwalUmum
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Umum gagal diubah',
                'data' => null
            ], 400);
        }
    }
    //Hapus jadwal umum (Manajer Operasional)
    public function delete(Request $request, $id){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $jadwalUmum = jadwalUmum::find($id);
        if(is_null($jadwalUmum)){
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Umum tidak ditemukan',
                'data' => null
            ], 400);
        }
        if($jadwalUmum->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Umum berhasil dihapus',
                'data' => $jadwalUmum
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Umum gagal dihapus',
                'data' => null
            ], 400);
        }
    }
    //Tampil semua jadwal umum (Umum)
    public function index(){
        $jadwalUmum = DB::table('jadwal_umums')
            ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
            ->join('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->select('jadwal_umums.*', 'instrukturs.nama as nama_instruktur', 'instrukturs.alamat as alamat_instruktur','instrukturs.tgl_lahir as tgl_lahir_instruktur', 'instrukturs.no_telp as no_telp_instruktur', 'instrukturs.username as username_instruktur' ,'kelas.nama as nama_kelas', 'kelas.harga as harga_kelas')
            ->orderByRaw("FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    $item->jam_mulai = date('H:i', strtotime($item->jam_mulai));
                    return $item;
                })->sortBy('jam_mulai');
            });
        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal Umum',
            'data' => $jadwalUmum
        ], 200);
    }
}
