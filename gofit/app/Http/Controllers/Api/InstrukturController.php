<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\instruktur;
use App\Models\pegawai;

class InstrukturController extends Controller
{
    //cek apakah pegawai
    public function cekPegawai(Request $request){
        $user = pegawai::where('id', $request->user()->id)->first();
        if(is_null($user)){
            return false;
        }else{
            return true;
        }
    }
    //cek apakah admin
    public function cekAdmin(Request $request){
        $user = pegawai::where('id', $request->user()->id)->first();
        if(is_null($user) || $user->jabatan_id != 2){
           return false;
       }else{
           return true;
       }
   }
   //cek apakah kasir
   public function cekKasir(Request $request){
        $user = pegawai::where('id', $request->user()->id)->first();
        if(is_null($user) || $user->jabatan_id != 3){
            return false;
        }else{
            return true;
        }
    }
   //Tampil semua instruktur (hanya admin)
    public function index(Request $request){
         if(!self::cekPegawai($request)){
              return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
              ], 401);
         }
         $instruktur = DB::table('instrukturs')
            ->where('deleted_at', null)
            ->get();
         return response()->json([
              'success' => true,
              'message' => 'Daftar Instruktur',
              'data' => $instruktur
         ], 200);
    }
    //Tampil semua instruktur kecuali diri sendiri (instruktur token)
    public function indexFiltered(Request $request){
        $instruktur = DB::table('instrukturs')
            ->where('id', '!=', $request->user()->id)
            ->where('deleted_at', null)
            ->get();
        return response()->json([
             'success' => true,
             'message' => 'Daftar Instruktur',
             'data' => $instruktur
        ], 200);
   }
    //Register instruktur (hanya admin)
    public function register(Request $request){
        if(!self::cekAdmin($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'tgl_lahir' => 'required|date|date_format:Y-m-d',
            'no_telp' => 'required|string',
            'username' => 'required|unique:instrukturs,username|unique:members,id|unique:pegawais,username',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }

        $instruktur = new instruktur();
        $instruktur->nama = $request->nama;
        $instruktur->alamat = $request->alamat;
        $instruktur->tgl_lahir = $request->tgl_lahir;
        $instruktur->no_telp = $request->no_telp;
        $instruktur->username = $request->username;
        $instruktur->password = bcrypt($request->password);

        if($instruktur->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendaftar Instruktur',
                'data' => $instruktur
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar instruktur',
                'data' => null
            ], 400);
        }
    }
    //Tampil data instruktur (hanya admin)
    public function find(Request $request){
        if(!self::cekAdmin($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $instruktur = instruktur::where('id', $request->data)
            ->where('deleted_at', null)
            ->orWhere('nama', 'like', '%'.$request->data.'%')
            ->orWhere('tgl_lahir', 'like', '%'.$request->data.'%')
            ->orWhere('no_telp', 'like', '%'.$request->data.'%')
            ->orWhere('username', 'like', '%'.$request->data.'%')
            ->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Data Instruktur',
            'data' => $instruktur
        ], 200);
    }
    //ubah data instruktur (hanya admin)
    public function update(Request $request, $id){
        if(!self::cekAdmin($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $instruktur = instruktur::find($id);
        if(is_null($instruktur)){
            return response()->json([
                'success' => false,
                'message' => 'Instruktur tidak ditemukan',
                'data' => null
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'tgl_lahir' => 'required|date|date_format:Y-m-d',
            'no_telp' => 'required|string',
            'username' => 'required|unique:instrukturs,username,'.$instruktur->id.'|unique:members,id|unique:pegawais,username',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }

        $instruktur->nama = $request->nama;
        $instruktur->alamat = $request->alamat;
        $instruktur->tgl_lahir = $request->tgl_lahir;
        $instruktur->no_telp = $request->no_telp;
        $instruktur->username = $request->username;

        if($instruktur->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data instruktur',
                'data' => $instruktur
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah data instruktur',
                'data' => null
            ], 400);
        }
    }
    //Hapus data instruktur (hanya admin)
    public function delete(Request $request, $id){
        if(!self::cekAdmin($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $instruktur = instruktur::find($id);
        if(is_null($instruktur)){
            return response()->json([
                'success' => false,
                'message' => 'Instruktur tidak ditemukan',
                'data' => null
            ], 400);
        }
        if($instruktur->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data instruktur',
                'data' => $instruktur
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data instruktur',
                'data' => null
            ], 400);
        }
    }
    //Ubah password Instruktur berdasarkan token
    public function ubahPassword(Request $request){
        $instruktur = instruktur::find($request->user()->id);
        if(is_null($instruktur)){
            return response()->json([
                'success' => false,
                'message' => 'Instruktur tidak ditemukan',
                'data' => null
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'password_baru' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => "Password lama dan password baru tidak boleh kosong",
                'data' => $validator->errors()
            ], 400);
        }
        if(Hash::check($request->password, $instruktur->password)){
            $instruktur->password = bcrypt($request->password_baru);
            if($instruktur->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah password',
                    'data' => $instruktur
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
    //Tampil profile instruktur berdasarkan token
    public function showProfile(Request $request){
        $instruktur = instruktur::find($request->user()->id);
        if(is_null($instruktur)){
            return response()->json([
                'success' => false,
                'message' => 'Instruktur tidak ditemukan',
                'data' => null
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Informasi Profile Instruktur',
            'data' => $instruktur
        ], 200);
    }
    
}
