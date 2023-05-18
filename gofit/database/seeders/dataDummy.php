<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\jadwal_harian as jadwalHarian;
use App\Models\izin_instruktur as izinInstruktur;
use App\Models\instruktur as instruktur;
use App\Models\booking_gym as bookingGym;

class dataDummy extends Seeder
{
    public function cekJadwalInstruktur(String $jam_mulai, String $hari,int $id){
        $instrukturPeganti = instruktur::find($id);
        if(is_null($instrukturPeganti)){
            return false;
        }
        $jadwalUmum = jadwalUmum::where('instruktur_id', $id)
            ->where('hari', $hari)
            ->where('jam_mulai', '>' ,Carbon::parse($jam_mulai)->subHour()->format('H:i'))
            ->where('jam_mulai', '<' ,Carbon::parse($jam_mulai)->addHour()->format('H:i'))
            ->first();
        $izin_instruktur = izinInstruktur::where('instruktur_penganti_id', $id)
            ->leftJoin('jadwal_umums', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')    
            ->where('jadwal_umums.hari', $hari)
            ->where('jadwal_umums.jam_mulai', '>' ,Carbon::parse($jam_mulai)->subHour()->format('H:i'))
            ->where('jadwal_umums.jam_mulai', '<' ,Carbon::parse($jam_mulai)->addHour()->format('H:i'))
            ->first();
        if(is_null($jadwalUmum) && is_null($izin_instruktur)){
            return false;
        }else{
            return true;
        }
    }
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
        //Pegawai
        DB::table('pegawais')->insert([
            [
                'jabatan_id' => 1,
                'nama' => 'Filbert',
                'alamat' => 'Jl. Centralpark No. 9 Yogyakarta',
                'tgl_lahir' => '1988-04-23',
                'no_telp' => '0812847312',
                'username' => 'ManajerOperasional',
                'password' => bcrypt('G0F1T'),
                'created_at' => '2023-01-01 00:00:00',
            ],[
                'jabatan_id' => 2,
                'nama' => 'Andi',
                'alamat' => 'Jl. Pajangan No. 1 Yogyakarta',
                'tgl_lahir' => '1998-02-20',
                'no_telp' => '08129131211',
                'username' => 'Admin01',
                'password' => bcrypt('4dminG0F1T'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'jabatan_id' => 2,
                'nama' => 'Budi',
                'alamat' => 'Jl. Gua Selarong No. 42 Yogyakarta',
                'tgl_lahir' => '2001-12-12',
                'no_telp' => '08119281123',
                'username' => 'Admin02',
                'password' => bcrypt('4dminG0F1T'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'jabatan_id' => 3,
                'nama' => 'Maya',
                'alamat' => 'Jl. Buntu No. 3 Yogyakarta',
                'tgl_lahir' => '2000-11-01',
                'no_telp' => '08132991123',
                'username' => 'Kasir01',
                'password' => bcrypt('K4sirG0F1T'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'jabatan_id' => 3,
                'nama' => 'Yunita',
                'alamat' => 'Jl. Sukajadi No. 01 Yogyakarta',
                'tgl_lahir' => '1999-05-12',
                'no_telp' => '0813412901923',
                'username' => 'Kasir02',
                'password' => bcrypt('K4sirG0F1T'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'jabatan_id' => 4,
                'nama' => 'Kowi',
                'alamat' => 'Jl. Bantul No. 21 Yogyakarta',
                'tgl_lahir' => '2003-10-23',
                'no_telp' => '0891218911',
                'username' => null,
                'password' => null,
                'created_at' => '2022-01-01 00:00:00',
            ], 
        ]);

        //Instruktur: 12 instruktur
        DB::table('instrukturs')->insert([[
                'nama' => 'Joon',
                'alamat' => 'Jl. Babarsari No. 11 Yogyakarta',
                'tgl_lahir' => '1985-05-12',
                'no_telp' => '081928111',
                'username' => 'Joon',
                'password' => bcrypt('120585'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'JK',
                'alamat' => 'Jl. Mahoni No. 08 Yogyakarta',
                'tgl_lahir' => '1992-04-12',
                'no_telp' => '081399991234',
                'username' => 'JK',
                'password' => bcrypt('120492'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Lisa',
                'alamat' => 'Jl. Sosko No. 121 Yogyakarta',
                'tgl_lahir' => '1993-09-01',
                'no_telp' => '081234125689',
                'username' => 'Lisa',
                'password' => bcrypt('010993'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Hobby',
                'alamat' => 'Jl. Bungur No. 123 Yogyakarta',
                'tgl_lahir' => '1992-09-05',
                'no_telp' => '089878123189',
                'username' => 'Hobby',
                'password' => bcrypt('050992'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'V',
                'alamat' => 'Jl. Amartha I No. 97 Yogyakarta',
                'tgl_lahir' => '1988-05-20',
                'no_telp' => '081340901010',
                'username' => 'V',
                'password' => bcrypt('200588'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Jenny',
                'alamat' => 'Jl. Komodo No. 21 Yogyakarta',
                'tgl_lahir' => '1996-07-24',
                'no_telp' => '089145678912',
                'username' => 'Jenny',
                'password' => bcrypt('240796'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Suga',
                'alamat' => 'Jl. Bunga No. 11 Yogyakarta',
                'tgl_lahir' => '1996-04-15',
                'no_telp' => '081134128756',
                'username' => 'Suga',
                'password' => bcrypt('150496'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Rose',
                'alamat' => 'Jl. Cempaka No. 03 Yogyakarta',
                'tgl_lahir' => '1994-03-12',
                'no_telp' => '08124290122',
                'username' => 'Rose',
                'password' => bcrypt('120394'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Jin',
                'alamat' => 'Jl. Kerucut No. 95 Yogyakarta',
                'tgl_lahir' => '1995-11-01',
                'no_telp' => '08112341901',
                'username' => 'Jin',
                'password' => bcrypt('011195'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Jisoo',
                'alamat' => 'Jl. Kinanti No. 16 Yogyakarta',
                'tgl_lahir' => '1994-10-20',
                'no_telp' => '0891234121',
                'username' => 'Jisoo',
                'password' => bcrypt('201094'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Jimin',
                'alamat' => 'Jl. Kaliurang Barat No. 32 Yogyakarta',
                'tgl_lahir' => '1990-02-16',
                'no_telp' => '0819021312',
                'username' => 'Jimin',
                'password' => bcrypt('160290'),
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Jessi',
                'alamat' => 'Jl. Apel No. 01 Yogyakarta',
                'tgl_lahir' => '1993-02-10',
                'no_telp' => '081341009111',
                'username' => 'Jessi',
                'password' => bcrypt('100293'),
                'created_at' => '2022-01-01 00:00:00',
            ],
        ]);

        //Kelas: 19 kelas
        DB::table('kelas')->insert([[
                'nama' => 'SPINE Corrector',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'MUAYTHAI',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'PILATES',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'ASTHANGA',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Body Combat',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'ZUMBA',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'HATHA',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Wall Swing',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Basic Swing',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Bellydance',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'BUNGEE',
                'harga' => 200000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Yogalates',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'BOXING',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Calisthenics',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Pound Fit',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Trampoline Workout',
                'harga' => 200000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Yoga For Kids',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Abs Pilates',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],[
                'nama' => 'Swing For Kids',
                'harga' => 150000,
                'created_at' => '2022-01-01 00:00:00',
            ],
        ]);

         //Jadwal Umum
         DB::table('jadwal_umums')->insert([[ 
                'kelas_id' => 1,
                'instruktur_id' => 1,
                'hari' => 'Monday',
                'jam_mulai' => '08:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 2,
                'instruktur_id' => 2,
                'hari' => 'Monday',
                'jam_mulai' => '09:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 3,
                'instruktur_id' => 3,
                'hari' => 'Tuesday',
                'jam_mulai' => '08:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 4,
                'instruktur_id' => 4,
                'hari' => 'Tuesday',
                'jam_mulai' => '09:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 5,
                'instruktur_id' => 5,
                'hari' => 'Wednesday',
                'jam_mulai' => '08:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 6,
                'instruktur_id' => 6,
                'hari' => 'Wednesday',
                'jam_mulai' => '08:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 7,
                'instruktur_id' => 7,
                'hari' => 'Wednesday',
                'jam_mulai' => '09:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 8,
                'instruktur_id' => 8,
                'hari' => 'Thursday',
                'jam_mulai' => '08:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 9,
                'instruktur_id' => 9,
                'hari' => 'Thursday',
                'jam_mulai' => '09:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 7,
                'instruktur_id' => 9,
                'hari' => 'Friday',
                'jam_mulai' => '08:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 10,
                'instruktur_id' => 10,
                'hari' => 'Friday',
                'jam_mulai' => '09:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 11,
                'instruktur_id' => 11,
                'hari' => 'Saturday',
                'jam_mulai' => '08:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 12,
                'instruktur_id' => 3,
                'hari' => 'Saturday',
                'jam_mulai' => '09:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 13,
                'instruktur_id' => 2,
                'hari' => 'Saturday',
                'jam_mulai' => '09:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 14,
                'instruktur_id' => 1,
                'hari' => 'Sunday',
                'jam_mulai' => '09:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 10,
                'instruktur_id' => 10,
                'hari' => 'Monday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 15,
                'instruktur_id' => 4,
                'hari' => 'Monday',
                'jam_mulai' => '18:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 14,
                'instruktur_id' => 1,
                'hari' => 'Tuesday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 1,
                'instruktur_id' => 7,
                'hari' => 'Tuesday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 16,
                'instruktur_id' => 8,
                'hari' => 'Tuesday',
                'jam_mulai' => '18:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 17,
                'instruktur_id' => 12,
                'hari' => 'Wednesday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 9,
                'instruktur_id' => 9,
                'hari' => 'Wednesday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 11,
                'instruktur_id' => 11,
                'hari' => 'Wednesday',
                'jam_mulai' => '18:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 18,
                'instruktur_id' => 6,
                'hari' => 'Thursday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 5,
                'instruktur_id' => 5,
                'hari' => 'Thursday',
                'jam_mulai' => '18:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 7,
                'instruktur_id' => 7,
                'hari' => 'Friday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 2,
                'instruktur_id' => 2,
                'hari' => 'Friday',
                'jam_mulai' => '18:30',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 19,
                'instruktur_id' => 12,
                'hari' => 'Saturday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 11,
                'instruktur_id' => 11,
                'hari' => 'Saturday',
                'jam_mulai' => '17:00',
                'created_at' => '2022-01-01 00:00:00',
            ],[ 
                'kelas_id' => 16,
                'instruktur_id' => 8,
                'hari' => 'Saturday',
                'jam_mulai' => '18:30',
                'created_at' => '2022-01-01 00:00:00',
            ],
        ]);

        //member
        $namaMember = ['10589_Ricky Junaidi','Arianna Mcknight','Tommy-Lee Carroll','Zakaria Slater','Ophelia Fisher','Esme Mack','Cleo Buckley','Kyra Barnett','Brianna Sanchez','Honey Lucas','Hugo Ortiz','Mahir Pena','Karina Sheppard','Simeon Archer','Alma Oneal','Susie Connolly','Uzair Shepherd','Ria Thornton','Roisin Sullivan','Isaiah Wang','Darcie Stevenson',];
        $jalanMember =['Karya Utama', 'Sejati Damai', 'Megah Ria', 'Cendekia', 'Babarsari', 'Centralpark', 'Sinar Kasih', 'Abadi', 'Buntu', 'Klaten'];
        $start_dateBorn = '1990-01-01';
        $end_dateBorn = '2004-12-31';
        $start_dateBorn = new Carbon($start_dateBorn);
        $end_dateBorn = new Carbon($end_dateBorn);

        $start_date2 = '2023-01-01';
        $end_date2 = '2023-01-07';
        $start_date2 = new Carbon($start_date2);
        $end_date2 = new Carbon($end_date2);
        //Akun default 
        DB::table('members')->insert([
            [
                'nama' => $namaMember[0],
                'alamat' => 'Jl. ' . $jalanMember[rand(0, count($jalanMember) - 1)] . ' No. ' . rand(1,30)  . ' Yogyakarta',
                'tgl_lahir' => '2002-06-02',
                'no_telp' => '08117601123',
                'email' => '200710589@students.uajy.ac.id',
                'username' => $namaMember[0],
                'password' => bcrypt(Carbon::parse('2002-06-02')->format('dmy')),
                'created_at' => '2023-01-01 00:00:00',
            ],
        ]);
        //aktivasi akun default
        $activedDate = Carbon::parse('2023-01-02')->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
        $member = DB::table('members')
            ->where('username', $namaMember[0])
            ->get()->first();
        DB::table('transaksis')->insert([
            'pegawai_id' => 'P0' . rand(4,5),
            'member_id' => $member->id,
            'jenis_transaksi_id' => 1,
            'created_at' => $activedDate,
        ]);
        DB::table('members')
            ->where('id', $member->id)
            ->update(['deactived_membership_at' => $activedDate->addYear(),]);
        //generate akun member dan aktivasi
        for($id = 1; $id < count($namaMember); $id++){
            $bornDateRand = Carbon::createFromTimestamp(rand($start_dateBorn->timestamp, $end_dateBorn->timestamp));
            $joinDateRand = Carbon::createFromTimestamp(rand($start_date2->timestamp, $end_date2->timestamp))->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
            $phone_numberRand = '08';
            for ($i = 0; $i < 8; $i++) {
                $phone_numberRand .= rand(0, 9);
            }
            DB::table('members')->insert([
                [
                    'nama' => $namaMember[$id],
                    'alamat' => 'Jl. ' . $jalanMember[rand(0, count($jalanMember) - 1)] . ' No. ' . rand(1,30)  . ' Yogyakarta',
                    'tgl_lahir' => $bornDateRand,
                    'no_telp' => $phone_numberRand,
                    'email' => $namaMember[$id] . '@gmail.com',
                    'username' => $namaMember[$id],
                    'password' => bcrypt($bornDateRand->format('dmy')),
                    'created_at' => $joinDateRand,
                ],
            ]);
            //aktivasi akun
            $activedDate = $joinDateRand->addDay()->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
            $member = DB::table('members')
                ->where('username', $namaMember[$id])
                ->get()->first();
            DB::table('transaksis')->insert([
                'pegawai_id' => 'P0' . rand(4,5),
                'member_id' => $member->id,
                'jenis_transaksi_id' => 1,
                'created_at' => $activedDate,
            ]);
            DB::table('members')
                ->where('id', $member->id)
                ->update(['deactived_membership_at' => $activedDate->addYear(),]);
        }

        //Ajukan Izin Instruktur
        $start_date = Carbon::parse('2023-01-01')->startOfWeek(Carbon::SUNDAY)->addDay();
        $end_date = Carbon::now();
        $list_keterangan_izin = ['Ada jadwal mengajar di gym lain', 'Nikahan', 'Sakit', 'Capek', 'Lelah', 'Ada urusan keluarga', 'Ada urusan lain', 'Ada acara', 'Ada rapat', 'Ada tugas', 'Ada kegiatan', 'Ada acara keluarga', 'Ada acara lain', 'Ada tugas kuliah', 'Ada tugas kampus', 'Ada tugas lain', 'Ada tugas kantor'];
        for($date = $start_date; $date <= $end_date; $date->addDays(rand(3, 30))){
            $jadwalUmum = DB::table('jadwal_umums')
                ->where('hari', '=' , $date->format('l'))
                ->get();
            $jadwalUmumRand = $jadwalUmum[rand(0, count($jadwalUmum) - 1)];
            //cara instruktur penganti
            $instruktur_penganti_id = rand(1,12);
            while($this->cekJadwalInstruktur($jadwalUmumRand->jam_mulai, $jadwalUmumRand->hari, $instruktur_penganti_id) && $instruktur_penganti_id != $jadwalUmumRand->instruktur_id){
                $instruktur_penganti_id++;
                if($instruktur_penganti_id > 12){
                    $instruktur_penganti_id = 1;
                }
            }
            if($instruktur_penganti_id > 12){
                continue;
            }
            DB::table('izin_instrukturs')->insert([
                'jadwal_umum_id' => $jadwalUmumRand->id,
                'instruktur_pengaju_id' => $jadwalUmumRand->instruktur_id,
                'instruktur_penganti_id' => $instruktur_penganti_id,
                'tanggal_izin' => $date,
                'keterangan' => $list_keterangan_izin[rand(0, count($list_keterangan_izin) - 1)],
                'created_at' => $date->setTime(rand(8, 20), rand(0, 59), rand(0, 59)),
            ]);
        }

        //random confirmed izin instruktur
        $ListIzin = DB::table('izin_instrukturs')
            ->where('is_confirmed', '=', 0)
            ->get();
        for($i = 0; $i < count($ListIzin); $i++){
            $random = rand(1, 10);
            //30% ditolak 70% diterima
            if($random > 7){
                DB::table('izin_instrukturs')
                    ->where('id', $ListIzin[$i]->id)
                    ->update(['is_confirmed' => 1]);
            }else{
                DB::table('izin_instrukturs')
                    ->where('id', $ListIzin[$i]->id)
                    ->update(['is_confirmed' => 2]);
            }
        }
        
        $start_date = Carbon::parse('2023-01-01')->startOfWeek(Carbon::SUNDAY)->addDay();
        $end_date = Carbon::now();
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            //Jadwal Harian
            if(Carbon::parse($date)->format('l') == 'Sunday'){
                $start_dateJadwalHarian = Carbon::parse($date)->startOfWeek(Carbon::SUNDAY)->addDay();
                $end_dateJadwalHarian =  Carbon::parse($date)->startOfWeek(Carbon::SUNDAY)->addDays(7);
                for($dateJadwalHarian = $start_dateJadwalHarian; $dateJadwalHarian->lte($end_dateJadwalHarian); $dateJadwalHarian->addDay()) {
                    $jadwalUmum = DB::table('jadwal_umums')
                        ->join('instrukturs', 'jadwal_umums.instruktur_id', '=', 'instrukturs.id')
                        ->where('jadwal_umums.hari', Carbon::parse($dateJadwalHarian)->format('l'))
                        ->where('instrukturs.deleted_at', null)
                        ->select('jadwal_umums.*')
                        ->get();
                    for($index = 0; $index < count($jadwalUmum); $index++){
                        $jadwalHarian = new jadwalHarian;
                        $jadwalHarian->jadwal_umum_id = $jadwalUmum[$index]->id;
                        $jadwalHarian->tanggal = $dateJadwalHarian;
                        
                        $izinInstruktur = izinInstruktur::where('jadwal_umum_id', $jadwalUmum[$index]->id)
                            ->where('tanggal_izin', $dateJadwalHarian)
                            ->where('is_confirmed', 2)
                            ->first();
                        if(!is_null($izinInstruktur)){
                            $jadwalHarian->status_id = 2;
                        }
                        $jadwalHarian->save();  
                        
                        //liburkan beberapa jadwal harian 20% libur 80% tidak
                        $randProbLibur = rand(1,10);
                        if($randProbLibur > 8){
                            $jadwalHarian->status_id = 1;
                            $jadwalHarian->save();
                        }
                    }
                }
            }
        }

        //Booking Gym
        // $start_date = Carbon::parse('2022-01-09')->startOfWeek(Carbon::SUNDAY)->addDay();
        // $end_date = Carbon::now();
        // for($date = $start_date; $date->lte($end_date); $date->addDay()) {
        //     //loop member
        //     for($idMember = 0; $idMember < count($namaMember); $idMember++){
        //         //Data Member
        //         $member = DB::table('members')
        //             ->where('username', $namaMember[$idMember])
        //             ->first();
        //         //Transaksi Deposit Reguler
        //         //Transaksi Deposit Kelas
        //         //Booking Gym
        //         //Booking kelas
        //         $randMemberAct = rand(1,10);
        //         if($randMemberAct < 3){

        //         }
        //     }
        // }
    }
}
