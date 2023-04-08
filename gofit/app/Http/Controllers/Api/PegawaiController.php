<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\pegawai;

class PegawaiController extends Controller
{
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
            'password' => 'required|string',
            'password_baru' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
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
                'data' => null
            ], 400);
        }
    }
}
