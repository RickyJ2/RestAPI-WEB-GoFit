<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

use App\Models\pegawai;
use App\Models\instruktur;
use App\Models\member;

class AuthController extends Controller
{
    public function loginWeb(Request $request){
        $loginData = $request->all();

        $validator = Validator::make($loginData, [
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Username dan Password harus diisi',
                'data' => $validator->errors()
            ], 400);
        }

        $user = pegawai::where('username', $loginData['username'])->first();

        if(!is_null($user) && Hash::check($loginData['password'], $user->password)){
            $token = $user->createToken('authToken')->accessToken;
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => $user,
                'token' => $token
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'data' => null
            ], 401);
        }
    }
}
