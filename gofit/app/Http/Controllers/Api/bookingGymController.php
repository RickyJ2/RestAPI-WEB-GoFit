<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\member;
use App\Models\booking_gym as bookingGym;

class bookingGymController extends Controller
{
    //cek aktivasi member
    public function cekMemberActivation(Request $request){
        $member = member::find($request->user()->id);
        if(is_null($member) || $member->deactived_membership_at == null){
           return true;
        }else{
           return false;
       }
    }
    //cek sudah booking hari ini
    public function cekSelfBook(Request $request){
        $booking = bookingGym::where('member_id', $request->user()->id)
            ->where('tgl_booking', Carbon::parse($request->tgl_booking)->format('Y-m-d'))
            ->where('is_cancelled', false)
            ->first();
        if(is_null($booking)){
            return false;
        }else{
            return true;
        }
    }
    //cek kuota
    public function cekKuota(Request $request){
        $booking = bookingGym::where('sesi_gym_id', $request->sesi_gym_id)
            ->where('tgl_booking', Carbon::parse($request->tgl_booking)->format('Y-m-d'))
            ->where('is_cancelled', false)
            ->count();
        if($booking >= 10){
            return true;
        }else{
            return false;
        }
    }
    //tambah booking
    public function add(Request $request){
        if(self::cekMemberActivation($request)){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses atau belum mengaktifkan membership',
                'data' => null
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'sesi_gym_id' => 'required',
            'tgl_booking' => 'required|date_format:Y-m-d',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => null
            ], 400);
        }
        if(self::cekSelfBook($request)){
            return response()->json([
                'success' => false,
                'message' => [
                    'tgl_booking' => ['Anda sudah booking gym pada tanggal ini'],
                ],
                'data' => null
            ], 400);
        }
        if(self::cekKuota($request)){
            return response()->json([
                'success' => false,
                'message' => [
                   'sesi_gym_id' => ['Kuota sesi gym sudah penuh'],
            ],
                'data' => null
            ], 400);
        }
        $booking = new bookingGym();
        $booking->member_id = $request->user()->id;
        $booking->sesi_gym_id = $request->sesi_gym_id;
        $booking->tgl_booking = $request->tgl_booking;
        if($booking->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil booking',
                'data' => $booking
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal booking',
                'data' => null
            ], 401);
        }
    }
    //membatalkan booking gym
    public function cancel(Request $request){
        $booking = bookingGym::find($request->id);
        if(is_null($booking)){
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
                'data' => null
            ], 400);
        }
        if($booking->member_id != $request->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak punya akses',
                'data' => null
            ], 400);
        }
        if($booking->is_cancelled){
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah dibatalkan',
                'data' => null
            ], 400);
        }
        if(Carbon::now()->format('Y-m-d') >= $booking->tgl_booking){
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah tidak bisa dibatalkan',
                'data' => null
            ], 400);
        }
        $booking->is_cancelled = true;
        if($booking->save()){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membatalkan booking',
                'data' => $booking
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan booking',
                'data' => null
            ], 400);
        }
    }
    //booking Gym Member
    public function show(Request $request){
        $booking = DB::table('booking_gyms')
            ->join('sesi_gyms', 'booking_gyms.sesi_gym_id', '=', 'sesi_gyms.id')
            ->where('booking_gyms.member_id', $request->user()->id)
            ->select('booking_gyms.*', 'sesi_gyms.jam_mulai', 'sesi_gyms.jam_selesai')
            ->orderBy('booking_gyms.created_at', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'List booking',
            'data' => $booking
        ], 200);
    }
}
