<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use PhpParser\Node\Stmt\Continue_;

class dataDummy extends Seeder
{
    public function generateDepositReguler() {
        $min = 1000000;
        $max = 10000000;
        $interval = 500000;
        $range = ($max - $min) / $interval;
        $randomIndex = rand(0, $range);
        $randomNumber = $min + ($randomIndex * $interval);
        return $randomNumber;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Presensi
        // for($presensiInstruktur_Id = 1; $presensiInstruktur_Id <= $countJadwalHarian; $presensiInstruktur_Id++){
        //     $jadwalHarian = DB::table('jadwal_harians')->find($presensiInstruktur_Id);

        //     if($jadwalHarian->status_id == 1){
        //         continue;
        //     }

        //     $jadwalUmum = DB::table('jadwal_umums')->find($jadwalHarian->jadwal_umum_id);
        //     $instruktur = $jadwalUmum->instruktur_id;

        //     if($jadwalHarian->status_id == 2){
        //         $izinInstuktur = DB::table('izin_instrukturs')
        //             ->where('jadwal_harian_id', $presensiInstruktur_Id)
        //             ->get();
        //         $instruktur = $izinInstuktur->first()->instruktur_penganti_id;
        //     }
            
        //     $rand = rand(1,10);
        //     if($rand <= 7){
        //         $controlledRandMasukTime = rand(-15,0);
        //     }else{
        //         $controlledRandMasukTime = rand(1,30);
        //     }

        //     $masukTime = Carbon::parse($jadwalUmum->jam_mulai)->subMinutes($controlledRandMasukTime);
        //     $selesaiTime = Carbon::parse($jadwalUmum->jam_mulai)->addHours(2)->subMinutes(rand(-30, 15));

        //     DB::table('presensi_instrukturs')->insert([[ 
        //             'instruktur_id' => $instruktur,
        //             'jadwal_harian_id' => $presensiInstruktur_Id,
        //             'jenis_presensi' => 'masuk',
        //             'created_at' => $masukTime,
        //         ],[ 
        //             'instruktur_id' => $instruktur,
        //             'jadwal_harian_id' => $presensiInstruktur_Id,
        //             'jenis_presensi' => 'selesai',
        //             'created_at' => $selesaiTime,
        //         ],
        //     ]);
        // }
    }
}
