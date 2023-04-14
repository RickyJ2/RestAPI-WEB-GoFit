<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    //laporan pendapatan bulanan dalam 1 tahun

    //laporan aktivitas kelas bulanan
    //laporan aktivitas gym bulanan
    //laporan kinerja instruktur
}
