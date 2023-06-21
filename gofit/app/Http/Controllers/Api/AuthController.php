<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
            $token = $user->createToken('authToken')->plainTextToken;
            $tokenString = substr($token, strpos($token, '|') + 1);
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => $tokenString,
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'data' => null
            ], 401);
        }
    }

    public function loginMobile(Request $request){
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
    
        if(!is_null(member::where('id', $loginData['username'])->where('deleted_at', null)->first())){
            $user = member::where('id', $loginData['username'])->first();
        } else if(!is_null(instruktur::where('username', $loginData['username'])->where('deleted_at', null)->first())){
            $user = instruktur::where('username', $loginData['username'])->first();
        } else if(!is_null(pegawai::where('username', $loginData['username'])->where('jabatan_id', 1)->first())){
            $user = pegawai::where('username', $loginData['username'])->where('jabatan_id', 1)->first();
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'data' => null
            ], 401);
        }
    
        if(!is_null($user) && Hash::check($loginData['password'], $user->password)){
            $token = $user->createToken('authToken')->plainTextToken;
            $tokenString = substr($token, strpos($token, '|') + 1);
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => $tokenString,
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Username dan password salah',
                'data' => null
            ], 401);
        }
    }

    public function getUserMobile(Request $request){
        $pegawai = pegawai::find($request->user()->id);
        $instruktur = instruktur::find($request->user()->id);
        $member = DB::table('members')
            ->join('kelas', 'members.kelas_deposit_kelas_paket_id', '=', 'kelas.id')
            ->select('members.*', 'kelas.nama as kelas_deposit_kelas_paket')
            ->where('members.id', $request->user()->id)
            ->first();
        $user = null;
        $role = null;
    
        if(!is_null($member)){
            $user = $member;
            $role = 'member';
        } else if(!is_null($instruktur)){
            $user = $instruktur;
            $role = 'instruktur';
        } else if(!is_null($pegawai)){
            $user = $pegawai;
            $role = 'MO';
        }
        
        if(!is_null($user)){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendapatkan data user',
                'role' => $role,
                'data' => $user,
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan data user',
                'data' => $user,
                'id' => $request->user()->id,
            ], 401);
        }
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil',
            'data' => null
        ], 200);
    }
}
