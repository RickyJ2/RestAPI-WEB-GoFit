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
        //Jadwal Harian
        $start_date = '2023-01-01';
        $end_date = Carbon::now();
        $countJadwalHarian = 0;

        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        for($date = $start; $date->lte($end); $date->addDays(1,7)) {
            for($index = 1; $index <= 30; $index++){
                $jadwalUmum = DB::table('jadwal_umums')->find($index);
                if($jadwalUmum->hari == Carbon::parse($date)->format('l')){
                    DB::table('jadwal_harians')->insert([[ 
                        'jadwal_umum_id' => $index,
                        'tanggal' => $date,
                        'created_at' => '2023-01-01 00:00:00',
                    ],]);
                    $countJadwalHarian++;
                }
            }
        }

        //random jadwal harian kosong
        $jadwal_harian_id = 1;

        while($jadwal_harian_id < $countJadwalHarian){
            DB::table('jadwal_harians')
                ->where('id', $jadwal_harian_id)
                ->update(['status_id' => 1,]);
            
            $jadwal_harian_id += rand(20, 100);
        }
        
        
        //Izin_Instruktur
        $jadwal_harian_id = 1;

        while($jadwal_harian_id < $countJadwalHarian){
            $jadwalHarian = DB::table('jadwal_harians')->find($jadwal_harian_id);
            if($jadwalHarian->status_id == null){
                $jadwalUmum = DB::table('jadwal_umums')->find($jadwalHarian->jadwal_umum_id);
                $datePegajuan = Carbon::parse($jadwalHarian->tanggal)->subDays(rand(1,14));
    
                do {
                    $instrukturPeganti_id = rand(1, 12);
                    $cekNabrakJadwalUmum = DB::table('jadwal_umums')
                        ->where('instruktur_id',$instrukturPeganti_id)
                        ->where('jam_mulai', $jadwalUmum->jam_mulai)
                        ->where('hari', $jadwalUmum->hari)
                        ->get();
                    $cekNabrakJadwalPeganti = DB::table('izin_instrukturs')
                        ->join('jadwal_harians', 'izin_instrukturs.jadwal_harian_id', '=', 'jadwal_harians.id')
                        ->join('jadwal_umums', 'jadwal_harians.jadwal_umum_id', '=', 'jadwal_umums.id')
                        ->where('izin_instrukturs.instruktur_penganti_id',$instrukturPeganti_id)
                        ->where('jadwal_umums.jam_mulai', $jadwalUmum->jam_mulai)
                        ->where('jadwal_umums.hari', $jadwalUmum->hari)
                        ->get();
                } while ($instrukturPeganti_id == $jadwalUmum->instruktur_id && $cekNabrakJadwalUmum != null && $cekNabrakJadwalPeganti != null);
    
                DB::table('izin_instrukturs')->insert([[ 
                    'jadwal_harian_id' => $jadwal_harian_id,
                    'instruktur_pengaju_id' => $jadwalUmum->instruktur_id,
                    'instruktur_penganti_id' => $instrukturPeganti_id,
                    'is_confirmed' => true,
                    'created_at' => $datePegajuan,
                ],]);
                DB::table('jadwal_harians')
                    ->where('id', $jadwal_harian_id)
                    ->update(['status_id' => 2,]);
            }
            $jadwal_harian_id += rand(1, 40);
        }

        //Presensi
        for($presensiInstruktur_Id = 1; $presensiInstruktur_Id <= $countJadwalHarian; $presensiInstruktur_Id++){
            $jadwalHarian = DB::table('jadwal_harians')->find($presensiInstruktur_Id);

            if($jadwalHarian->status_id == 1){
                continue;
            }

            $jadwalUmum = DB::table('jadwal_umums')->find($jadwalHarian->jadwal_umum_id);
            $instruktur = $jadwalUmum->instruktur_id;

            if($jadwalHarian->status_id == 2){
                $izinInstuktur = DB::table('izin_instrukturs')
                    ->where('jadwal_harian_id', $presensiInstruktur_Id)
                    ->get();
                $instruktur = $izinInstuktur->first()->instruktur_penganti_id;
            }
            
            $rand = rand(1,10);
            if($rand <= 7){
                $controlledRandMasukTime = rand(-15,0);
            }else{
                $controlledRandMasukTime = rand(1,30);
            }

            $masukTime = Carbon::parse($jadwalUmum->jam_mulai)->subMinutes($controlledRandMasukTime);
            $selesaiTime = Carbon::parse($jadwalUmum->jam_mulai)->addHours(2)->subMinutes(rand(-30, 15));

            DB::table('presensi_instrukturs')->insert([[ 
                    'instruktur_id' => $instruktur,
                    'jadwal_harian_id' => $presensiInstruktur_Id,
                    'jenis_presensi' => 'masuk',
                    'created_at' => $masukTime,
                ],[ 
                    'instruktur_id' => $instruktur,
                    'jadwal_harian_id' => $presensiInstruktur_Id,
                    'jenis_presensi' => 'selesai',
                    'created_at' => $selesaiTime,
                ],
            ]);
        }
    }
}
