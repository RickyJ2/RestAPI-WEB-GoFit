<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\pegawai;

class PegawaiController extends Controller
{
    //dapat pegawai dari token
    public function index(Request $request){
        $pegawai = pegawai::find($request->user()->id);
        if(is_null($pegawai)){
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
                'data' => null
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan pegawai',
            'data' => $pegawai
        ], 200);
    }
    //Ubah password MO berdasarkan token
    public function ubahPassword(Request $request){
        $pegawai = pegawai::find($request->user()->id);
        if(is_null($pegawai) || $pegawai->jabatan_id != 1){
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan atau tidak dapat mengubah password',
                'data' => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'password_baru' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => "Password dan Password Baru harus diisi",
                'data' => $validator->errors(),
            ], 400);
        }
        if(Hash::check($request->password, $pegawai->password)){
            $pegawai->password = bcrypt($request->password_baru);
            if($pegawai->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah password',
                    'data' => $pegawai
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah password',
                    'data' => null
                ], 400);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Password lama salah',
                'data' => [
                    'password' => ['Password lama salah'],
                ],
            ], 400);
        }
    }
}
