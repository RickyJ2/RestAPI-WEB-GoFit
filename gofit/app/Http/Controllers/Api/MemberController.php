<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\member;
use App\Models\pegawai;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            ->where('deleted_at', null)
            ->select('members.id','members.nama', 'members.alamat', 'members.tgl_lahir', 'members.no_telp', 'members.email', DB::raw('IFNULL(members.deactived_membership_at, "Belum Aktif") as deactived_membership_at'), 'members.deposit_reguler', 'members.deposit_kelas_paket', DB::raw('IFNULL(members.deactived_deposit_kelas_paket, "Belum Aktif") as deactived_deposit_kelas_paket'), DB::raw('IFNULL(kelas.nama , "-") as kelas_deposit_kelas_paket'))
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Member',
            'data' => $member
        ], 200);
    }
    public function indexMembershipExpired(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 401);
        }
        $member = DB::table('members')
        ->leftJoin('kelas', 'members.kelas_deposit_kelas_paket_id', '=', 'kelas.id')
        ->where('deleted_at', null)
        ->where('deactived_membership_at', '<', Carbon::now())
        ->select('members.id','members.nama', 'members.alamat', 'members.tgl_lahir', 'members.no_telp', 'members.email', DB::raw('IFNULL(members.deactived_membership_at, "Belum Aktif") as deactived_membership_at'), 'members.deposit_reguler', 'members.deposit_kelas_paket', DB::raw('IFNULL(members.deactived_deposit_kelas_paket, "Belum Aktif") as deactived_deposit_kelas_paket'), DB::raw('IFNULL(kelas.nama , "-") as kelas_deposit_kelas_paket'))
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Member',
            'data' => $member
        ], 200);
    }
    public function indexDepositKelasExpired(Request $request){
        if(!self::cekKasir($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null,
            ], 401);
        }
        $member = DB::table('members')
        ->leftJoin('kelas', 'members.kelas_deposit_kelas_paket_id', '=', 'kelas.id')
        ->where('deleted_at', null)
        ->where('deactived_deposit_kelas_paket', '<', Carbon::now())
        ->select('members.id','members.nama', 'members.alamat', 'members.tgl_lahir', 'members.no_telp', 'members.email', DB::raw('IFNULL(members.deactived_membership_at, "Belum Aktif") as deactived_membership_at'), 'members.deposit_reguler', 'members.deposit_kelas_paket', DB::raw('IFNULL(members.deactived_deposit_kelas_paket, "Belum Aktif") as deactived_deposit_kelas_paket'), DB::raw('IFNULL(kelas.nama , "-") as kelas_deposit_kelas_paket'))
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
            'email' => 'required|string|email:rfc,dns',
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
        $member->password = bcrypt($request->password);

        if($member->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendaftar member',
                'data' => null,
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
            ->where('deleted_at', null)
            ->Where('members.id', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.nama', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.tgl_lahir', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.no_telp', 'LIKE', '%'. $request->data .'%')
            ->orWhere('members.email', 'LIKE', '%'. $request->data .'%')
            ->select('members.id','members.nama', 'members.alamat', 'members.tgl_lahir', 'members.no_telp', 'members.email', DB::raw('IFNULL(members.deactived_membership_at, "Belum Aktif") as deactived_membership_at'), 'members.deposit_reguler', 'members.deposit_kelas_paket', DB::raw('IFNULL(members.deactived_deposit_kelas_paket, "Belum Aktif") as deactived_deposit_kelas_paket'), DB::raw('IFNULL(kelas.nama , "-") as kelas_deposit_kelas_paket'))
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
    
}
