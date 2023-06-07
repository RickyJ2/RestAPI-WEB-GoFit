<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('cekInstruktur', 'App\Http\Controllers\Api\JadwalUmumController@cekJadwalInstruktur');

Route::get('infoUmum', 'App\Http\Controllers\Api\InfoUmumController@show');
Route::post('loginWeb', 'App\Http\Controllers\Api\AuthController@loginWeb');
Route::post('loginMobile', 'App\Http\Controllers\Api\AuthController@loginMobile');

//Tampil Jadwal Umum + Kelas + Instruktur
Route::get('jadwalUmum/index', 'App\Http\Controllers\Api\JadwalUmumController@index');

Route::group(['middleware' => 'auth:sanctum'], function(){
    //getUser mobile
    Route::get('getUserMobile', 'App\Http\Controllers\Api\AuthController@getUserMobile');
    //logout Web dan Mobile
    Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');

    Route::get('pegawai/index', 'App\Http\Controllers\Api\PegawaiController@index');

    //member (hak akses kasir)
    Route::get('member/index', 'App\Http\Controllers\Api\MemberController@index');
    Route::get('member/indexMembershipExpired', 'App\Http\Controllers\Api\MemberController@indexMembershipExpired');
    Route::get('member/indexDepositKelasExpired', 'App\Http\Controllers\Api\MemberController@indexDepositKelasExpired');
    Route::post('member/register', 'App\Http\Controllers\Api\MemberController@register');
    Route::post('member/find', 'App\Http\Controllers\Api\MemberController@find');
    Route::put('member/update/{id}', 'App\Http\Controllers\Api\MemberController@update');
    Route::delete('member/{id}', 'App\Http\Controllers\Api\MemberController@delete');
    Route::put('member/resetPassword/{id}', 'App\Http\Controllers\Api\MemberController@resetPasswordMember');
    //reset Data expire membership, deposit kelas member (hak akses kasir)
    Route::get('member/resetMemberExpired', 'App\Http\Controllers\Api\resetController@resetMemberExpired');
    //member token
    Route::get('member/showProfile', 'App\Http\Controllers\Api\MemberController@showProfile');
    
    //promo (hak akses kasir)
    Route::get('promo/index', 'App\Http\Controllers\Api\promoController@index');

    //transaksi (hak akses kasir)
    Route::post('transaksi/aktivasi', 'App\Http\Controllers\Api\transaksiController@activationTransaksi');
    Route::post('transaksi/depositReguler', 'App\Http\Controllers\Api\transaksiController@depositRegulerTransaksi');
    Route::post('transaksi/depositKelasPaket', 'App\Http\Controllers\Api\transaksiController@depositKelasPaketTransaksi');

    //instruktur (hak akses admin)
    Route::get('instruktur/index', 'App\Http\Controllers\Api\InstrukturController@index');
    Route::post('instruktur/register', 'App\Http\Controllers\Api\InstrukturController@register');
    Route::post('instruktur/find', 'App\Http\Controllers\Api\InstrukturController@find');
    Route::put('instruktur/update/{id}', 'App\Http\Controllers\Api\InstrukturController@update');
    Route::delete('instruktur/{id}', 'App\Http\Controllers\Api\InstrukturController@delete');
    //reset akumulasi terlambat instruktur
    Route::get('instruktur/resetAkumulasiTerlambat', 'App\Http\Controllers\Api\resetController@resetAkumulasiTerlambat');
    //instruktur token
    Route::get('instruktur/indexFiltered', 'App\Http\Controllers\Api\InstrukturController@indexFiltered');
    Route::put('instruktur/ubahPassword', 'App\Http\Controllers\Api\InstrukturController@ubahPassword');
    Route::get('instruktur/showProfile', 'App\Http\Controllers\Api\InstrukturController@showProfile');
    //Pegawai Manajer Operasional token
    Route::put('pegawai/ubahPassword', 'App\Http\Controllers\Api\PegawaiController@ubahPassword');
    
    //Tampil daftar kelas
    Route::get('kelas/index', 'App\Http\Controllers\Api\KelasController@index');

    //Jadwal Umum (hak akses MO)
    Route::post('jadwalUmum/add', 'App\Http\Controllers\Api\JadwalUmumController@add');
    Route::put('jadwalUmum/update/{id}', 'App\Http\Controllers\Api\JadwalUmumController@update');
    Route::delete('jadwalUmum/{id}', 'App\Http\Controllers\Api\JadwalUmumController@delete');

    //daftar Jadwal Umum hari ini
    Route::post('jadwalUmum/getThisDay', 'App\Http\Controllers\Api\JadwalUmumController@getThisDay');
    
    //Jadwal Harian (hak akses MO)
    Route::post('jadwalHarian/libur/{id}', 'App\Http\Controllers\Api\JadwalHarianController@updateLiburJadwalHarian');
    Route::get('jadwalHarian/generate', 'App\Http\Controllers\Api\JadwalHarianController@generateJadwalHarian');
    Route::post('jadwalHarian/find', 'App\Http\Controllers\Api\JadwalHarianController@find');
    //Tampil Jadwal Harian + Jadwal Umum  + Kelas + Instruktur + Status
    Route::get('jadwalHarian/index', 'App\Http\Controllers\Api\JadwalHarianController@index');
    //Tampil Jadwal Harian minggu ini dengan filter (utk member)
    Route::get('jadwalHarian/indexThisWeek', 'App\Http\Controllers\Api\JadwalHarianController@indexThisWeek');
    //Tampil Jadwal Harian hari ini (MO)
    Route::get('jadwalHarian/showToday', 'App\Http\Controllers\Api\JadwalHarianController@showToday');
    //Update jam Mulai dan jam selesai jadwal harian
    Route::put('jadwalHarian/updateJamMulai/{id}', 'App\Http\Controllers\Api\JadwalHarianController@updateJamMulai');
    Route::put('jadwalHarian/updateJamSelesai/{id}', 'App\Http\Controllers\Api\JadwalHarianController@updateJamSelesai');
    //Tampil Jadwal Harian hari ini untuk instruktur
    Route::get('jadwalHarian/showTodaySchedule', 'App\Http\Controllers\Api\JadwalHarianController@showTodaySchedule');
    //Tampil History Jadwal Harian untuk instruktur
    Route::get('jadwalHarian/show', 'App\Http\Controllers\Api\JadwalHarianController@show');
    //Tampil History Jadwal Harian untuk instruktur dengan filter
    Route::get('jadwalHarian/showFilter/{year}/{month}', 'App\Http\Controllers\Api\JadwalHarianController@showFiltered');

    //Izin Instruktur (MO)
    Route::get('izinInstruktur/index', 'App\Http\Controllers\Api\izinInstrukturController@index');
    Route::put('izinInstruktur/verifikasi/{id}', 'App\Http\Controllers\Api\izinInstrukturController@updateVerifIzin');
    //Izin Instruktur (Instruktur)
    Route::post('izinInstruktur/add', 'App\Http\Controllers\Api\izinInstrukturController@add');
    Route::get('izinInstruktur/show', 'App\Http\Controllers\Api\izinInstrukturController@show');
    
    //Booking Kelas (Kasir)
    Route::get('bookingKelas/index', 'App\Http\Controllers\Api\bookingKelasController@index');
    //List member booking kelas (Instruktur)
    Route::get('bookingKelas/getListMember/{id}', 'App\Http\Controllers\Api\bookingKelasController@getListMember');
    //Booking Kelas (Member)
    Route::get('bookingKelas/show', 'App\Http\Controllers\Api\bookingKelasController@show');
    Route::post('bookingKelas/showFilter', 'App\Http\Controllers\Api\bookingKelasController@showFilter');
    Route::post('bookingKelas/add', 'App\Http\Controllers\Api\bookingKelasController@add');
    Route::post('bookingKelas/cancel', 'App\Http\Controllers\Api\bookingKelasController@cancel');
    //Presensi Booking Kelas (Instruktur)
    Route::post('bookingKelas/updatePresent', 'App\Http\Controllers\Api\transaksiController@updatePresentKelas');

    //sesi Gym
    Route::get('sesiGym/index', 'App\Http\Controllers\Api\sesiGymController@index');

    //Booking Gym (Kasir)
    Route::get('bookingGym/index', 'App\Http\Controllers\Api\bookingGymController@index');
    Route::post('bookingGym/updatePresent', 'App\Http\Controllers\Api\transaksiController@updatePresent');
    //Booking Gym (Member)
    Route::get('bookingGym/show', 'App\Http\Controllers\Api\bookingGymController@show');
    Route::post('bookingGym/showFilter', 'App\Http\Controllers\Api\bookingGymController@showFilter');
    Route::post('bookingGym/add', 'App\Http\Controllers\Api\bookingGymController@add');
    Route::post('bookingGym/cancel', 'App\Http\Controllers\Api\bookingGymController@cancel');

    //Laporan
    Route::get('laporanPendapatan/{year}', 'App\Http\Controllers\Api\laporanController@laporanPendapatan');
    Route::get('laporanKelas/{year}/{month}', 'App\Http\Controllers\Api\laporanController@laporanKelas');
    Route::get('laporanGym/{year}/{month}', 'App\Http\Controllers\Api\laporanController@laporanGym');
    Route::get('laporanKinerjaInstruktur/{year}/{month}', 'App\Http\Controllers\Api\laporanController@laporanKinerjaInstruktur');
});
