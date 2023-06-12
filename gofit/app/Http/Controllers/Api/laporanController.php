<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\transaksi;
use App\Models\detail_transaksi_deposit_kelas_paket as detailTransaksiDepostiKelasPaket;;
use App\Models\detail_transaksi_deposit_reguler as detailTransaksiDepostiReguler;
use App\Models\booking_kelas as bookingKelas;
use App\Models\izin_instruktur as izinInstruktur;
use App\Models\instruktur;
use App\Models\pegawai;
use App\Models\member;
use App\Models\booking_gym as bookingGym;
use App\Models\presensi_instruktur as presensiInstruktur;
use App\Models\jadwal_harian as jadwalHarian;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\kelas;

class laporanController extends Controller
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
    //laporan pendapatan bulanan dalam 1 tahun
    public function laporanPendapatan(Request $request, $year){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $monthData = DB::table('transaksis')
            ->selectRaw('MONTH(transaksis.created_at) AS month, YEAR(transaksis.created_at) AS year, 
                SUM(CASE WHEN jenis_transaksi_id = 1 THEN 1 ELSE 0 END) AS count_jenis_1,
                SUM(CASE WHEN jenis_transaksi_id = 2 THEN detail_transaksi_deposit_regulers.nominal ELSE 0 END) AS nominal_regulers_sum,
                SUM(CASE WHEN jenis_transaksi_id = 3 THEN detail_transaksi_deposit_kelas_pakets.total ELSE 0 END) AS total_kelas_pakets_sum')
            ->leftJoin('detail_transaksi_deposit_regulers', 'transaksis.id', '=', 'detail_transaksi_deposit_regulers.no_nota')
            ->leftJoin('detail_transaksi_deposit_kelas_pakets', 'transaksis.id', '=', 'detail_transaksi_deposit_kelas_pakets.no_nota')
            ->whereYear('transaksis.created_at', $year)
            ->groupBy('year', 'month')
            ->get();

        $result = [];
        $totalAll = 0;
        foreach ($monthData as $month) {
            $monthName = Carbon::createFromDate(null, $month->month, null)->monthName;
            $result[] = [
                'bulan' => $monthName,
                'aktivasi' => $month->count_jenis_1 * 3000000,
                'deposit' => $month->nominal_regulers_sum + $month->total_kelas_pakets_sum,
                'total' => $month->count_jenis_1 * 3000000 + $month->nominal_regulers_sum + $month->total_kelas_pakets_sum
            ];
            $totalAll += $month->count_jenis_1 * 3000000 + $month->nominal_regulers_sum + $month->total_kelas_pakets_sum;
        }

        $result[] = [
            'bulan' => 'Total',
            'aktivasi' => 'Total',
            'deposit' => 'Total',
            'total' => $totalAll
        ];

        return response()->json([
            'success' => true,
            'message' => 'Laporan pendapatan',
            'data' => $result
        ], 200);
    }
    //laporan aktivitas kelas bulanan
    public function laporanKelas(Request $request, $year, $month){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }

        $bookingKelas = DB::table('jadwal_harians')
            ->join('jadwal_umums', 'jadwal_harians.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->join('kelas', 'jadwal_umums.kelas_id', '=', 'kelas.id')
            ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
            ->leftJoin('booking_kelas', 'booking_kelas.jadwal_harian_id', '=', 'jadwal_harians.id')
            ->leftJoin('status_jadwal_harians', 'jadwal_harians.status_id', '=', 'status_jadwal_harians.id')
            ->leftJoin('izin_instrukturs', function ($join) {
                $join->on('jadwal_umums.id', '=', 'izin_instrukturs.jadwal_umum_id')
                    ->on('jadwal_harians.tanggal', '=', 'izin_instrukturs.tanggal_izin')
                    ->where('izin_instrukturs.is_confirmed', 2);
            })
            ->leftJoin('instrukturs as instrukturs_penganti', 'izin_instrukturs.instruktur_penganti_id', '=', 'instrukturs_penganti.id')
            ->select('kelas.nama as jenis_kelas', 
                    DB::raw('CASE WHEN jadwal_harians.status_id = 2 THEN instrukturs_penganti.nama ELSE instrukturs.nama END AS instruktur'),
                    DB::raw('COUNT(CASE WHEN booking_kelas.is_cancelled = false THEN booking_kelas.id END) as jumlah_booking'),
                    DB::raw('COUNT(CASE WHEN jadwal_harians.status_id = 1 THEN jadwal_harians.id END) AS jumlah_libur'),
                )
            ->whereYear('jadwal_harians.tanggal', $year)
            ->whereMonth('jadwal_harians.tanggal', $month)
            ->groupBy('jenis_kelas', 'instruktur')
            ->get();

        $data = [];
        foreach ($bookingKelas as $booking) {
            $data[] = [
                'kelas' => $booking->jenis_kelas,
                'instruktur' => $booking->instruktur,
                'jumlah_peserta' => $booking->jumlah_booking,
                'jumlah_libur'=> $booking->jumlah_libur,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan aktivitas kelas',
            'data' => $data
        ], 200);

    }
    //laporan aktivitas gym bulanan
    public function laporanGym(Request $request, $year, $month){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime("$year-$month-01"));

        $countBookings = DB::table('booking_gyms')
            ->select(DB::raw('DATE(tgl_booking) AS booking_date'), DB::raw('COUNT(*) AS total_bookings'))
            ->whereBetween('tgl_booking', [$startDate, $endDate])
            ->groupBy('booking_date')
            ->get();

        $data = [];
        $sum = 0;
        foreach ($countBookings as $countBooking) {
            $bookingDate = $countBooking->booking_date;
            $totalBookings = $countBooking->total_bookings;
            $sum += $totalBookings;

            $data[] = [
                'tanggal' => $bookingDate,
                'jumlah_member' => $totalBookings
            ];
        }

        $data[] = [
            'tanggal' => 'Total',
            'jumlah_member' => $sum
        ];

        return response()->json([
            'success' => true,
            'message' => 'Laporan Gym',
            'data' => $data
        ], 200);
    }
    //laporan kinerja instruktur
    public function laporanKinerjaInstruktur(Request $request, $year, $month){
        if(!self::cekManajerOperasional($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }

        // $data = DB::table('jadwal_harians')
        //     ->join('jadwal_umums', 'jadwal_harians.jadwal_umum_id', '=', 'jadwal_umums.id')
        //     ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
        //     ->leftJoin('izin_instrukturs', function ($join) {
        //         $join->on('jadwal_umums.id', '=', 'izin_instrukturs.jadwal_umum_id')
        //             ->on('jadwal_harians.tanggal', '=', 'izin_instrukturs.tanggal_izin')
        //             ->where('izin_instrukturs.is_confirmed', 2);
        //     })
        //     ->select(
        //         DB::raw('CASE WHEN jadwal_harians.status_id = 2 THEN instrukturs_penganti.nama ELSE instrukturs.nama END AS nama_instruktur'),
        //         DB::raw('COUNT(CASE WHEN jadwal_harians.status_id = NULL THEN jadwal_harians.id END) AS jumlah_hadir'),
        //         DB::raw('COUNT(CASE WHEN jadwal_harians.status_id = 1 THEN jadwal_harians.id END) AS jumlah_libur'),
        //         DB::raw('SUM(jadwal_harians.akumulasi_terlambat) AS total_waktu_terlambat')
        //     )
        //     ->whereYear('jadwal_harians.tanggal', $year)
        //     ->whereMonth('jadwal_harians.tanggal', $month)
        //     ->groupBy('nama_instruktur')
        //     ->orderBy('total_waktu_terlambat', 'ASC')
        //     ->get();

        $data = DB::table('instrukturs')
            ->select(
                DB::raw('CASE WHEN jadwal_harians.status_id = 2 THEN instrukturs_penganti.nama ELSE instrukturs.nama END AS nama_instruktur'),
                DB::raw('COUNT(jadwal_harians.id) AS jumlah_hadir'),
                DB::raw('COUNT(CASE WHEN jadwal_harians.status_id = 1 THEN jadwal_harians.id END) AS jumlah_libur'),
                DB::raw('SUM(jadwal_harians.akumulasi_terlambat) AS total_waktu_terlambat')
            )
            ->leftJoin('jadwal_umums', 'instrukturs.id', '=', 'jadwal_umums.instruktur_id')
            ->leftJoin('jadwal_harians', 'jadwal_harians.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->leftJoin('izin_instrukturs', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')
            ->leftJoin('instrukturs AS instrukturs_penganti', function ($join) {
                $join->on('instrukturs_penganti.id', '=', 'izin_instrukturs.instruktur_penganti_id')
                    ->where('jadwal_harians.status_id', '=', 2);
            })
            ->whereYear('jadwal_harians.tanggal', $year)
            ->whereMonth('jadwal_harians.tanggal', $month)
            ->groupBy('nama_instruktur')
            ->orderBy('total_waktu_terlambat', 'ASC')
            ->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Laporan Instruktur',
            'data' => $data
        ], 200);
    }
}
