<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\izin_instruktur as izinInstruktur;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\jadwal_harian as jadwalHarian;
use App\Models\pegawai;

class JadwalHarianController extends Controller
{
    //cek apakah sudah generate jadwal harian
    public function cekStatusGenerateAutomatic(){
        $jadwalHarian = jadwalHarian::where('tanggal', '>', Carbon::now()->startOfWeek()->format('Y-m-d'))
            ->first();
        if(is_null($jadwalHarian)){
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
    //generate jadwal harian
    public function generateJadwalHarian(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        if(self::cekStatusGenerateAutomatic()){
            return response()->json([
                'success' => false,
                'message' => 'Jadwal harian minggu ini sudah di generate',
                'data' => null,
            ], 400);
        }
        $start_date = Carbon::now()->startOfWeek()->addDays(7);
        $end_date =  Carbon::now()->startOfWeek()->addDays(13);
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $jadwalUmum = jadwalUmum::where('hari', Carbon::parse($date)->format('l'))->get();
            for($index = 0; $index < count($jadwalUmum); $index++){
                $jadwalHarian = new jadwalHarian;
                $jadwalHarian->jadwal_umum_id = $jadwalUmum[$index]->id;
                $jadwalHarian->tanggal = $date;
                
                $izinInstruktur = izinInstruktur::where('jadwal_umum_id', $jadwalUmum[$index]->id)
                    ->where('tanggal_izin', $date)
                    ->where('is_confirmed', true)
                    ->first();
                if(!is_null($izinInstruktur)){
                    $jadwalHarian->status_id = 2;
                }
                $jadwalHarian->save();    
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Jadwal harian berhasil di generate',
            'data' => null
        ], 200);
    }
    //Meliburkan jadwal harian
    public function updateLiburJadwalHarian(Request $request, $id){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $jadwalHarian = jadwalHarian::find($id);
        if(is_null($jadwalHarian)){
            return response()->json([
                'success' => false,
                'message' => 'Jadwal harian tidak ditemukan',
                'data' => null
            ], 400);
        }
        $jadwalHarian->status_id = 1;
        $jadwalHarian->save();
        return response()->json([
            'success' => true,
            'message' => 'Jadwal harian berhasil di liburkan',
            'data' => null
        ], 200);
    }
    //mencari jadwal harian (MO)
    public function find(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->startOfWeek()->addDays(6);
        $jadwalHarian = DB::table('jadwal_harians')
            ->join('jadwal_umums', 'jadwal_harians.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->join('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
            ->leftJoin('status_jadwal_harians', 'jadwal_harians.status_id', '=', 'status_jadwal_harians.id')
            ->leftJoin('izin_instrukturs', function ($join) {
                $join->on('jadwal_umums.id', '=', 'izin_instrukturs.jadwal_umum_id')
                    ->on('jadwal_harians.tanggal', '=', 'izin_instrukturs.tanggal_izin')
                    ->where('izin_instrukturs.is_confirmed', true);
            })
            ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs_penganti.id')
            ->select('jadwal_harians.id', 'jadwal_harians.tanggal', 'jadwal_umums.jam_mulai', 'kelas.nama', 'instrukturs.nama as instruktur', 'status_jadwal_harians.jenis_status', 'instrukturs_penganti.nama as instruktur_penganti')
            ->where('jadwal_harians.tanggal', '>=', $start_date->format('Y-m-d'))
            ->where('jadwal_harians.tanggal', '<=', $end_date->format('Y-m-d'))

            ->orWhere('jadwal_harians.tanggal', 'like', '%'.$request->data.'%')
            ->orWhere('jadwal_umums.jam_mulai', 'like', '%'.$request->data.'%')
            ->orWhere('kelas.nama', 'like', '%'.$request->data.'%')
            ->orWhere('instrukturs.nama', 'like', '%'.$request->data.'%')
            ->orWhere('status_jadwal_harians.jenis_status', 'like', '%'.$request->data.'%')
            ->orWhere('instrukturs_penganti.nama', 'like', '%'.$request->data.'%')

            ->orderBy('jadwal_harians.tanggal')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal harian',
            'data' => $jadwalHarian
        ], 200);
    }
    //Tampilkan jadwal harian hari ini
    public function showToday(Request $request){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $jadwalHarian = DB::table('jadwal_harians')
            ->join('jadwal_umums', 'jadwal_harians.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->join('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
            ->leftJoin('status_jadwal_harians', 'jadwal_harians.status_id', '=', 'status_jadwal_harians.id')
            ->leftJoin('izin_instrukturs', function ($join) {
                $join->on('jadwal_umums.id', '=', 'izin_instrukturs.jadwal_umum_id')
                    ->on('jadwal_harians.tanggal', '=', 'izin_instrukturs.tanggal_izin')
                    ->where('izin_instrukturs.is_confirmed', true);
            })
            ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs_penganti.id')
            ->select('jadwal_harians.id', 'jadwal_harians.tanggal', 'jadwal_umums.jam_mulai', 'kelas.nama', 'instrukturs.nama as instruktur', 'status_jadwal_harians.jenis_status', 'instrukturs_penganti.nama as instruktur_penganti')
            ->where('jadwal_harians.tanggal', '=', Carbon::now()->format('Y-m-d'))
            ->orderBy('jadwal_harians.tanggal')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal harian hari ini',
            'data' => $jadwalHarian
        ], 200);
    }
    //tampilkan jadwal harian
    public function index(){
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->startOfWeek()->addDays(6);
        $jadwalHarian = DB::table('jadwal_harians')
            ->join('jadwal_umums', 'jadwal_harians.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->join('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
            ->leftJoin('status_jadwal_harians', 'jadwal_harians.status_id', '=', 'status_jadwal_harians.id')
            ->leftJoin('izin_instrukturs', function ($join) {
                $join->on('jadwal_umums.id', '=', 'izin_instrukturs.jadwal_umum_id')
                    ->on('jadwal_harians.tanggal', '=', 'izin_instrukturs.tanggal_izin')
                    ->where('izin_instrukturs.is_confirmed', true);
            })
            ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs_penganti.id')
            ->select('jadwal_harians.id', 'jadwal_harians.tanggal', 'jadwal_umums.jam_mulai', 'kelas.nama', 'instrukturs.nama as instruktur', 'status_jadwal_harians.jenis_status', 'instrukturs_penganti.nama as instruktur_penganti')
            ->where('jadwal_harians.tanggal', '>=', $start_date->format('Y-m-d'))
            ->where('jadwal_harians.tanggal', '<=', $end_date->format('Y-m-d'))
            ->orderBy('jadwal_harians.tanggal')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal Harian',
            'data' => $jadwalHarian,
        ], 200);
    }
}
