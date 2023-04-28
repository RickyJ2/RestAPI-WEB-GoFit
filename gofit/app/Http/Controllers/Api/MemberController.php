<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\member;
use App\Models\pegawai;

class MemberController extends Controller
{
    //cek apakah kasir
    public function cekKasir(Request $request){
         $user = pegawai::where('id', $request->user()->id)->first();
         if(is_null($user) || $user->jabatan_id != 3){
            return false;
        }else{
            return true;
        }
    }
    //Tampil semua member (hanya kasir)
    public function index(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 401);
        }
        $member = DB::table('members')
            ->leftJoin('kelas', 'members.kelas_deposit_kelas_paket_id', '=', 'kelas.id')
            ->select('members.id','members.nama', 'members.alamat', 'members.tgl_lahir', 'members.no_telp', 'members.email', 'members.username', DB::raw('IFNULL(members.deactived_membership_at, "Belum Aktif") as deactived_membership_at'), 'members.deposit_reguler', 'members.deposit_kelas_paket', DB::raw('IFNULL(members.deactived_deposit_kelas_paket, "Belum Aktif") as deactived_deposit_kelas_paket'), DB::raw('IFNULL(kelas.nama , "-") as kelas_deposit_kelas_paket'))
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Member',
            'data' => $member
        ], 200);
    }
    //Register member (hanya kasir)
    public function register(Request $request){
        if(!self::cekKasir($request)){
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
            'email' => 'required|string|email',
            'username' => 'required|unique:members,username|unique:instrukturs,username|unique:pegawais,username',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null,
            ], 400);
        }

        $member = new member();
        $member->nama = $request->nama;
        $member->alamat = $request->alamat;
        $member->tgl_lahir = $request->tgl_lahir;
        $member->no_telp = $request->no_telp;
        $member->email = $request->email;
        $member->username = $request->username;
        $member->password = bcrypt($request->password);

        if($member->save()){
            $member = member::where('username', $request->username)->first();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendaftar member',
                'data' => $member,
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar member',
                'data' => null
            ], 400);
        }
    }
    //Tampil data member (hanya kasir)
    public function find(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }

        $member = DB::table('members')
            ->leftJoin('kelas', 'members.kelas_deposit_kelas_paket_id', '=', 'kelas.id')
            ->where('members.id', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.nama', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.tgl_lahir', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.no_telp', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.email', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.username', 'LIKE', '%'. $request->data .'%')
            ->select('members.id','members.nama', 'members.alamat', 'members.tgl_lahir', 'members.no_telp', 'members.email', 'members.username', DB::raw('IFNULL(members.deactived_membership_at, "Belum Aktif") as deactived_membership_at'), 'members.deposit_reguler', 'members.deposit_kelas_paket', DB::raw('IFNULL(members.deactived_deposit_kelas_paket, "Belum Aktif") as deactived_deposit_kelas_paket'), DB::raw('IFNULL(kelas.nama , "-") as kelas_deposit_kelas_paket'))
            ->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Informasi Data Member',
            'data' => $member
        ], 200);
    }
    //Ubah data member (hanya kasir)
    public function update(Request $request, $id){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $member = member::where('id', $id)->first();
        if(is_null($member)){
            return response()->json([
                'success' => false,
                'message' => 'Data member tidak ditemukan',
                'data' => null
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'tgl_lahir' => 'required|date',
            'no_telp' => 'required|string',
            'email' => 'required|string|email',
            'username' => 'required|unique:members,username,'.$member->id .',id|unique:instrukturs,username|unique:pegawais,username',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null,
            ], 400);
        }

        $member->nama = $request->nama;
        $member->alamat = $request->alamat;
        $member->tgl_lahir = $request->tgl_lahir;
        $member->no_telp = $request->no_telp;
        $member->email = $request->email;
        $member->username = $request->username;

        if($member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data member',
                'data' => $member
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah data member',
                'data' => null
            ], 400);
        }
    }
    //Hapus data member (hanya kasir)
    public function delete(Request $request, $id){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 401);
        }
        $member = member::where('id', $id)->first();
        if(is_null($member)){
            return response()->json([
                'success' => false,
                'message' => 'Data member tidak ditemukan',
                'data' => null
            ], 400);
        }
        if($member->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data member',
                'data' => $member
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data member',
                'data' => null
            ], 400);
        }
    }
    //Reset password member (hanya member)
    public function resetPasswordMember(Request $request, $id){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $member = member::where('id', $id)->first();
        $member -> password = bcrypt(Carbon::parse($member -> tgl_lahir)->format('dmy'));
        if($member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mereset password member',
                'data' => $member
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password member',
                'data' => null
            ], 400);
        }
    }
    //Tampil profile member berdasarkan token
    public function showProfile(Request $request){
        $member = member::where('id', $request->user()->id)->first();
        if(is_null($member)){
            return response()->json([
                'success' => false,
                'message' => 'Data member tidak ditemukan',
                'data' => null
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Informasi Profile Member',
            'data' => $member
        ], 200);
    }
    //mendeactive membership yg kadarluasa
    public function deactiveMember(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $member = member::where('deactived_membership_at', '!=', null)
            ->where('deactived_membership_at', '<', Carbon::now())
            ->get();
        foreach($member as $m){
            $m->deactived_membership_at = null;
            $m->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendekatif membership',
            'data' => $member
        ], 200);
    }
    //mendeactive deposit kelas paket yg kadarluasa
    public function deactiveDepositKelasPaketMember(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        $member = member::where('deactived_deposit_kelas_paket_at', '!=', null)
            ->where('deactived_deposit_kelas_paket_at', '<', Carbon::now())
            ->get();
        foreach($member as $m){
            $m->deposit_kelas_paket = 0;
            $m->deactived_deposit_kelas_paket_at = null;
            $m->kelas_deposit_kelas_paket_id = null;
            $m->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendekatif deposit kelas paket',
            'data' => $member
        ], 200);
    }
}
