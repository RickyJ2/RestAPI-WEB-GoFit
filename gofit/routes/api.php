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

Route::get('infoUmum', 'App\Http\Controllers\Api\InfoUmumController@show');
Route::post('loginWeb', 'App\Http\Controllers\Api\AuthController@loginWeb');
Route::post('loginMobile', 'App\Http\Controllers\Api\AuthController@loginMobile');

//Tampil Jadwal Umum + Kelas + Instruktur
Route::get('jadwalUmum/index', 'App\Http\Controllers\Api\JadwalUmumController@index');

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');

    //member (hak akses kasir)
    Route::get('member/index', 'App\Http\Controllers\Api\MemberController@index');
    Route::post('member/register', 'App\Http\Controllers\Api\MemberController@register');
    Route::post('member/find', 'App\Http\Controllers\Api\MemberController@find');
    Route::put('member/update/{id}', 'App\Http\Controllers\Api\MemberController@update');
    Route::delete('member/{id}', 'App\Http\Controllers\Api\MemberController@delete');
    Route::put('member/resetPassword/{id}', 'App\Http\Controllers\Api\MemberController@resetPasswordMember');
    //member token
    Route::get('member/showProfile', 'App\Http\Controllers\Api\MemberController@showProfile');
    
    //instruktur (hak akses admin)
    Route::get('instruktur/index', 'App\Http\Controllers\Api\InstrukturController@index');
    Route::post('instruktur/register', 'App\Http\Controllers\Api\InstrukturController@register');
    Route::post('instruktur/find', 'App\Http\Controllers\Api\InstrukturController@find');
    Route::put('instruktur/update/{id}', 'App\Http\Controllers\Api\InstrukturController@update');
    Route::delete('instruktur/{id}', 'App\Http\Controllers\Api\InstrukturController@delete');
    //instruktur token
    Route::put('instruktur/ubahPassword', 'App\Http\Controllers\Api\InstrukturController@ubahPassword');
    Route::get('instruktur/showProfile', 'App\Http\Controllers\Api\InstrukturController@showProfile');
    //Pegawai Manajer Operasional token
    Route::post('pegawai/ubahPassword', 'App\Http\Controllers\Api\PegawaiController@ubahPassword');
    
    //Tampil daftar kelas
    Route::get('kelas/index', 'App\Http\Controllers\Api\KelasController@index');

    //Jadwal Umum (hak akses MO)
    Route::post('jadwalUmum/add', 'App\Http\Controllers\Api\JadwalUmumController@add');
    Route::put('jadwalUmum/update/{id}', 'App\Http\Controllers\Api\JadwalUmumController@update');
    Route::delete('jadwalUmum/{id}', 'App\Http\Controllers\Api\JadwalUmumController@delete');
    
    //Jadwal Harian (hak akses MO)
    Route::post('jadwalHarian/libur/{id}', 'App\Http\Controllers\Api\JadwalHarianController@updateLiburJadwalHarian');
    Route::get('jadwalHarian/generate', 'App\Http\Controllers\Api\JadwalHarianController@generateJadwalHarian');
    Route::post('jadwalHarian/find', 'App\Http\Controllers\Api\JadwalHarianController@find');
    //Tampil Jadwal Harian + Jadwal Umum  + Kelas + Instruktur + Status
    Route::get('jadwalHarian/index', 'App\Http\Controllers\Api\JadwalHarianController@index');
});
