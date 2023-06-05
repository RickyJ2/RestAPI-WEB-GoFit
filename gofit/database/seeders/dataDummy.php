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
use App\Models\booking_kelas as bookingKelas;
use App\Models\transaksi;
use App\Models\detail_transaksi_deposit_reguler as detailTransaksiDepositReguler;
use App\Models\detail_transaksi_deposit_kelas_paket as detailTransaksiDepositKelasPaket;
use App\Models\sesi_gym as sesiGym;
use App\Models\kelas;
use App\Models\member as Member;

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
    public function createTransaksi(String $member_id, String $jenis_transaksi_id, String $created_at){
        $pegawai_id = 'P0' . rand(4,5);
        if($jenis_transaksi_id != 5){
            DB::table('transaksis')->insert([
                'pegawai_id' => $pegawai_id,
                'member_id' => $member_id,
                'jenis_transaksi_id' => $jenis_transaksi_id,
                'created_at' => $created_at,
            ]);
            $transaksi = Transaksi::where('pegawai_id', $pegawai_id)
                ->where('member_id', $member_id)
                ->where('jenis_transaksi_id', $jenis_transaksi_id)
                ->where('created_at', $created_at)
                ->first();
        }else{
            DB::table('transaksis')->insert([
                'member_id' => $member_id,
                'jenis_transaksi_id' => $jenis_transaksi_id,
                'created_at' => $created_at,
            ]);
            $transaksi = Transaksi::where('member_id', $member_id)
                ->where('jenis_transaksi_id', $jenis_transaksi_id)
                ->where('created_at', $created_at)
                ->first();
        }
        return $transaksi;
    }
    public function generateDepositReguler() {
        $min = 500000;
        $max = 5000000;
        $interval = 50000;
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
        //member
        $namaMember = ['10589_Ricky Junaidi','Matthew Wilson', 'Noah Young', 'Ava Anderson', 'Daniel Moore', 'Sophia Taylor', 'James Robinson', 'Benjamin Miller', 'Charlotte White', 'Alexander Johnson', 'Michael Clark', 'Olivia Scott', 'Noah Taylor', 'Benjamin Harris', 'John Harris', 'Sophia Wilson', 'Alexander Davis', 'John Miller', 'Emily Martin', 'Matthew Lee', 'Olivia Davis', 'Daniel Wright', 'Emma Young', 'Isabella Robinson', 'Emily Taylor', 'Victoria Davis', 'James Davis', 'James Martin', 'Amelia Smith', 'Ethan Miller', 'Amelia Walker', 'Ethan Davis', 'Jane Wilson', 'Olivia White', 'Isabella Scott', 'Daniel Lewis', 'Noah Smith', 'Emily Lewis', 'Daniel Wilson', 'James Clark', 'Jane Moore', 'Isabella Harris', 'Michael Wilson', 'Sophia Smith', 'Benjamin Martin', 'Alexander Anderson', 'Amelia Davis', 'Victoria Walker', 'John Clark', 'Emma Walker', 'Matthew Lewis', 'Emily Smith', 'Arianna Mcknight','Tommy-Lee Carroll','Zakaria Slater','Ophelia Fisher','Esme Mack','Cleo Buckley','Kyra Barnett','Brianna Sanchez','Honey Lucas','Hugo Ortiz','Mahir Pena','Karina Sheppard','Simeon Archer','Alma Oneal','Susie Connolly','Uzair Shepherd','Ria Thornton','Roisin Sullivan','Isaiah Wang','Darcie Stevenson', ];
        $jalanMember =['Karya Utama', 'Sejati Damai', 'Megah Ria', 'Cendekia', 'Babarsari', 'Centralpark', 'Sinar Kasih', 'Abadi', 'Buntu', 'Klaten', 'Gunung Sahari', 'Kartini', 'Rawamangun', 'Tulip', 'Diponegoro', 'Gading', 'Pluit', 'Cempaka Putih', 'Kemang', 'Menteng', 'Dahlia', 'Sakura', 'Sudirman', 'Cilandak', 'Permata Hijau', 'Senayan', 'Kelapa Gading', 'Anggrek', 'Pegangsaan', 'Tanah Abang', 'Kuningan', 'Taman Sari', 'Cipinang', 'Surya Kencana', 'Menteng', 'Gajah Mada', 'Melati', 'Sunter', 'Puri Indah', 'Tebet', 'Cendrawasih', 'Thamrin', 'Kebon Jeruk', 'Cipete', 'Pahlawan', 'Kalibata', 'Bougenville', 'Cipete', 'Kemayoran', 'Gunung Sahari', 'Gatot Subroto', 'Menteng', 'Merdeka', 'Kamboja', 'Pasar Baru', 'Sudirman', 'Kamboja', 'Pancoran', 'Teratai', 'Seruni', 'Pasar Minggu', 'Flamboyan', 'Cendrawasih', 'Taman Sari',];
        $start_dateBorn = '1990-01-01';
        $end_dateBorn = '2004-12-31';
        $start_dateBorn = new Carbon($start_dateBorn);
        $end_dateBorn = new Carbon($end_dateBorn);

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
                'created_at' => '2022-01-01 00:00:00',
            ],
        ]);
        //aktivasi akun default
        $activedDate = Carbon::parse('2022-01-01')->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
        $member = Member::where('username', $namaMember[0])
            ->first();
        DB::table('transaksis')->insert([
            'pegawai_id' => 'P0' . rand(4,5),
            'member_id' => $member->id,
            'jenis_transaksi_id' => 1,
            'created_at' => $activedDate,
        ]);
        $member->deactived_membership_at = $activedDate->addYear();
        $member->save();
        //Deposit Reguler
        $transaksi = self::createTransaksi($member->id, 2, Carbon::parse('2022-01-01')->setTime(rand(8, 20), rand(0, 59), rand(0, 59)));
        $detailTransaksi = new detailTransaksiDepositReguler;
        $detailTransaksi->no_nota = $transaksi->id;       
        $detailTransaksi->nominal = self::generateDepositReguler();

        $member->deposit_reguler += $detailTransaksi->nominal;
        if($detailTransaksi->nominal >= 3000000){
            $member->deposit_reguler += 300000;
            $detailTransaksi->promo_id = 1;
        }
        $detailTransaksi->save();
        $member->save();
        //Deposit kelas Paket
        $transaksi = self::createTransaksi($member->id, 3, Carbon::parse('2022-01-01')->setTime(rand(8, 20), rand(0, 59), rand(0, 59)));
        $detailTransaksi = new detailTransaksiDepositKelasPaket;
        $detailTransaksi->no_nota = $transaksi->id;
        $detailTransaksi->kelas_id = rand(1,19);
        $detailTransaksi->nominal = rand(1, 10);
        $kelas = DB::table('kelas')
            ->where('id', $detailTransaksi->kelas_id)
            ->first();
        $detailTransaksi->total = $detailTransaksi->nominal * $kelas->harga;
        
        $member->deposit_kelas_paket += $detailTransaksi->nominal;
        $member->kelas_deposit_kelas_paket_id = $detailTransaksi->kelas_id;
        if($detailTransaksi->nominal < 10){
            $member->deactived_deposit_kelas_paket = Carbon::parse($transaksi->created_at)->addMonth();
        }else{
            $member->deactived_deposit_kelas_paket = Carbon::parse($transaksi->created_at)->addMonths(2);
        }
        if($detailTransaksi->nominal >= 10){
            $member->deposit_kelas_paket += 3;
            $detailTransaksi->promo_id = 2;
        }else if($detailTransaksi->nominal >= 5){
            $member->deposit_kelas_paket += 1;
            $detailTransaksi->promo_id = 3;
        }
        $detailTransaksi->save();
        $member->save();
        
        $start_date2 = '2022-01-01';
        $end_date2 = '2022-01-07';
        $start_date2 = new Carbon($start_date2);
        $end_date2 = new Carbon($end_date2);
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
            $activedDate = $joinDateRand->copy()->addDay()->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
            $member = Member::where('username', $namaMember[$id])
                ->first();
            DB::table('transaksis')->insert([
                'pegawai_id' => 'P0' . rand(4,5),
                'member_id' => $member->id,
                'jenis_transaksi_id' => 1,
                'created_at' => $activedDate,
            ]);
            DB::table('members')
                ->where('id', $member->id)
                ->update(['deactived_membership_at' => $activedDate->addYear()]);
            
            //Deposit Reguler
            $transaksi = self::createTransaksi($member->id, 2, $joinDateRand->setTime(rand(8, 20), rand(0, 59), rand(0, 59)));
            $detailTransaksi = new detailTransaksiDepositReguler;
            $detailTransaksi->no_nota = $transaksi->id;       
            $detailTransaksi->nominal = self::generateDepositReguler();

            $member->deposit_reguler += $detailTransaksi->nominal;
            if($detailTransaksi->nominal >= 3000000){
                $member->deposit_reguler += 300000;
                $detailTransaksi->promo_id = 1;
            }
            $detailTransaksi->save();
            $member->save();
            //Deposit kelas Paket
            $transaksi = self::createTransaksi($member->id, 3, $joinDateRand->setTime(rand(8, 20), rand(0, 59), rand(0, 59)));
            $detailTransaksi = new detailTransaksiDepositKelasPaket;
            $detailTransaksi->no_nota = $transaksi->id;
            $detailTransaksi->kelas_id = rand(1,19);
            $detailTransaksi->nominal = rand(1, 10);
            $kelas = DB::table('kelas')
                ->where('id', $detailTransaksi->kelas_id)
                ->first();
            $detailTransaksi->total = $detailTransaksi->nominal * $kelas->harga;
            
            $member->deposit_kelas_paket += $detailTransaksi->nominal;
            $member->kelas_deposit_kelas_paket_id = $detailTransaksi->kelas_id;
            if($detailTransaksi->nominal < 10){
                $member->deactived_deposit_kelas_paket = Carbon::parse($transaksi->created_at->copy())->addMonth();
            }else{
                $member->deactived_deposit_kelas_paket = Carbon::parse($transaksi->created_at->copy())->addMonths(2);
            }
            if($detailTransaksi->nominal >= 10){
                $member->deposit_kelas_paket += 3;
                $detailTransaksi->promo_id = 2;
            }else if($detailTransaksi->nominal >= 5){
                $member->deposit_kelas_paket += 1;
                $detailTransaksi->promo_id = 3;
            }
            $detailTransaksi->save();
            $member->save();
        }

        //Ajukan Izin Instruktur
        $start_date = Carbon::parse('2022-06-10')->startOfWeek(Carbon::SUNDAY)->addDay();
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

            //random konfirmasi 70% diterima 30% ditolak
            $randConfirmed = rand(1, 10);
            if($randConfirmed < 7) {
                $randConfirmed = 1;
            }else{
                $randConfirmed = 0;
            }

            DB::table('izin_instrukturs')->insert([
                'jadwal_umum_id' => $jadwalUmumRand->id,
                'instruktur_pengaju_id' => $jadwalUmumRand->instruktur_id,
                'instruktur_penganti_id' => $instruktur_penganti_id,
                'tanggal_izin' => $date,
                'keterangan' => $list_keterangan_izin[rand(0, count($list_keterangan_izin) - 1)],
                'is_confirmed' => $randConfirmed,
                'created_at' => $date->copy()->setTime(rand(8, 20), rand(0, 59), rand(0, 59)),
            ]);
        }
        
        $start_date = Carbon::parse('2022-06-10')->startOfWeek(Carbon::SUNDAY)->addDay();
        $end_date = Carbon::now();
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            //deactive membership yg kadarluasa & tambah transaksi aktivasi
            $member = Member::where('deactived_membership_at', '<', $date)
                ->get();
            foreach($member as $m){
                $m->deactived_membership_at = null;
                $m->save();
                //aktivasi
                $activedDate = $date->copy()->addDay()->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
                DB::table('transaksis')->insert([
                    'pegawai_id' => 'P0' . rand(4,5),
                    'member_id' => $m->id,
                    'jenis_transaksi_id' => 1,
                    'created_at' => $activedDate,
                ]);
                DB::table('members')
                    ->where('id', $m->id)
                    ->update(['deactived_membership_at' => $activedDate->copy()->addYear()]);
            }
            //deactive paket kelas yang kadarluasa && tambah transaksi deposit kelas paket
            $member = Member::where('deactived_deposit_kelas_paket', '<', $date)
                ->get();
            foreach($member as $m){
                $m->deposit_kelas_paket = 0;
                $m->deactived_deposit_kelas_paket = null;
                $m->kelas_deposit_kelas_paket_id = null;
                $m->save();
                //deposit kelas paket
                $transaksi = self::createTransaksi($m->id, 3, $date->copy()->addDay()->setTime(rand(8, 20), rand(0, 59), rand(0, 59)));
                $detailTransaksi = new detailTransaksiDepositKelasPaket;
                $detailTransaksi->no_nota = $transaksi->id;
                $detailTransaksi->kelas_id = rand(1,19);
                $detailTransaksi->nominal = rand(1, 10);
                $kelas = DB::table('kelas')
                    ->where('id', $detailTransaksi->kelas_id)
                    ->first();
                $detailTransaksi->total = $detailTransaksi->nominal * $kelas->harga;
                $detailTransaksi->created_at = Carbon::parse($transaksi->created_at->copy());
                
                $m->deposit_kelas_paket += $detailTransaksi->nominal;
                $m->kelas_deposit_kelas_paket_id = $detailTransaksi->kelas_id;
                if($detailTransaksi->nominal < 10){
                    $m->deactived_deposit_kelas_paket = Carbon::parse($transaksi->created_at)->addMonth();
                }else{
                    $m->deactived_deposit_kelas_paket = Carbon::parse($transaksi->created_at)->addMonths(2);
                }
                if($detailTransaksi->nominal >= 10){
                    $m->deposit_kelas_paket += 3;
                    $detailTransaksi->promo_id = 2;
                }else if($detailTransaksi->nominal >= 5){
                    $m->deposit_kelas_paket += 1;
                    $detailTransaksi->promo_id = 3;
                }
                $detailTransaksi->save();
                $m->save();
            }
            //tambah deposit reguler kalau lebih kecil dari 250k
            $member = Member::where('deposit_reguler', '<', 250000)
                ->get();
            foreach($member as $m){
                $transaksi = self::createTransaksi($m->id, 2, $date->copy()->addDay()->setTime(rand(8, 20), rand(0, 59), rand(0, 59)));
                $detailTransaksi = new detailTransaksiDepositReguler;
                $detailTransaksi->no_nota = $transaksi->id;       
                $detailTransaksi->nominal = self::generateDepositReguler();
                $detailTransaksi->created_at = Carbon::parse($transaksi->created_at->copy());

                $m->deposit_reguler += $detailTransaksi->nominal;
                if($detailTransaksi->nominal >= 3000000){
                    $m->deposit_reguler += 300000;
                    $detailTransaksi->promo_id = 1;
                }
                $detailTransaksi->save();
                $m->save();
            }
            
            //reset akumulasi terlambat instruktur
            if(Carbon::parse($date)->day == 1){
                $instruktur = instruktur::all();
                foreach($instruktur as $i){
                    $i->akumulasi_terlambat = 0;
                    $i->save();
                }
            }
            //Jadwal Harian
            if(Carbon::parse($date)->format('l') == 'Sunday'){
                $start_dateJadwalHarian = Carbon::parse($date->copy())->startOfWeek(Carbon::SUNDAY)->addDay();
                $end_dateJadwalHarian =  Carbon::parse($date->copy())->startOfWeek(Carbon::SUNDAY)->addDays(7);
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
                        $jadwalHarian->tanggal = $dateJadwalHarian->copy();
                        
                        $izinInstruktur = izinInstruktur::where('jadwal_umum_id', $jadwalUmum[$index]->id)
                            ->where('tanggal_izin', $dateJadwalHarian)
                            ->where('is_confirmed', 2)
                            ->first(); 
                        //liburkan beberapa jadwal harian 20% libur 80% tidak
                        $randProbLibur = rand(1,10);
                        if($randProbLibur > 8){
                            $jadwalHarian->status_id = 1;
                        }else {
                            $instruktur = Instruktur::find($jadwalUmum[$index]->instruktur_id);
                            if(!is_null($izinInstruktur)){
                                $jadwalHarian->status_id = 2;
                                $izinInstruktur = DB::table('izin_instrukturs')
                                    ->where('jadwal_umum_id', $jadwalUmum[$index]->id)
                                    ->where('tanggal_izin', $jadwalHarian->tanggal)
                                    ->where('is_confirmed', 2)
                                    ->first();
                                $instruktur = Instruktur::find($izinInstruktur->instruktur_penganti_id);
                            }
                            //update jam mulai kelas
                            list($hour, $minute) = explode(":", $jadwalUmum[$index]->jam_mulai);
                            $jadwalHarian->jam_mulai = Carbon::parse($dateJadwalHarian->copy())->setTime($hour, $minute, rand(0, 59))->subMinutes(rand(-15, 30));
                            //update akumulasi terlambat instruktur
                            if(Carbon::parse($jadwalHarian->jam_mulai)->gt(Carbon::parse($jadwalUmum[$index]->jam_mulai))){
                                $jadwalHarian->akumulasi_terlambat = Carbon::parse($jadwalHarian->jam_mulai)->diffInMinutes(Carbon::parse($jadwalUmum[$index]->jam_mulai));
                                $instruktur->akumulasi_terlambat += Carbon::parse($jadwalHarian->jam_mulai)->diffInMinutes(Carbon::parse($jadwalUmum[$index]->jam_mulai));
                                $instruktur->save();
                            }
                            //update jam selesai
                            $endTime = $jadwalHarian->jam_mulai->copy();
                            $jadwalHarian->jam_selesai = $endTime->copy()->addHour();
                        }
                        $jadwalHarian->save();
                    }
                }
            }
            //Booking Gym
            $idMember = range(0, count($namaMember) - 1);
            shuffle($idMember);
            for($sesiGym = 1; $sesiGym <= 7; $sesiGym++){
                $randBook = rand(1,10);
                for($index = 0; $index < $randBook; $index++){
                    $member = Member::where('username', $namaMember[$idMember[$index]])
                        ->first();
                    $bookingGym = new bookingGym;
                    $bookingGym->member_id = $member->id;
                    $bookingGym->tgl_booking = $date;
                    $bookingGym->sesi_gym_id = $sesiGym;
                    $bookingGym->created_at = Carbon::parse($date->copy())->subDays(rand(1,3))->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
                     //random 80% hadir 20% tidak hadir
                    $randConfirmed = rand(1, 10);
                    if($randConfirmed < 9) {
                        $sesiGymData = sesiGym::find($sesiGym);
                        list($hour, $minute) = explode(":", $sesiGymData->jam_mulai);
                        $bookingGym->present_at = Carbon::parse($date->copy())->setTime($hour, $minute, rand(0, 59))->subMinutes(rand(0, 30));
                        $transaksi = self::createTransaksi($member->id, 4, $bookingGym->present_at->copy());
                        $bookingGym->no_nota = $transaksi->id;
                    }
                    $bookingGym->save();
                }
                $idMember = array_slice($idMember, $randBook);
            }
            //Booking Kelas
            $jadwalHarian = jadwalHarian::where('tanggal', $date)
                ->where('status_id', '=', 2)
                ->orWhereNull('status_id')
                ->get();
            $idMember = range(0, count($namaMember) - 1);
            shuffle($idMember);
            foreach($jadwalHarian as $j){
                if(empty($idMember)) break;
                if(count($idMember) <= 10) $randBook = count($idMember);
                else
                    $randBook = rand(1,10);
                $presentAt = Carbon::parse($date->copy())->setTime($j->jam_mulai, rand(0, 59))->subMinutes(rand(0, 30));
                for($index = 0; $index < $randBook; $index++){
                    $member = Member::where('username', $namaMember[$idMember[$index]])
                        ->first();
                    $bookingKelas = new bookingKelas;
                    $bookingKelas->member_id = $member->id;
                    $bookingKelas->jadwal_harian_id = $j->id;
                    $bookingKelas->created_at = Carbon::parse($date->copy())->subDays(rand(1,3))->setTime(rand(8, 20), rand(0, 59), rand(0, 59));
                    //random 80% hadir 20% tidak hadir
                    $randConfirmed = rand(1, 10);
                    if($randConfirmed < 9) {
                        $bookingKelas->present_at = $presentAt->copy();   
                    }
                    //potong deposit
                    $transaksi = self::createTransaksi($member->id, 5, $presentAt->copy());
                    $bookingKelas->no_nota = $transaksi->id;
                    $jadwalUmum = jadwalUmum::find($j->jadwal_umum_id);
                    $kelas = kelas::find($jadwalUmum->kelas_id);
                    if($member->kelas_deposit_kelas_paket_id == $kelas->id && $member->deposit_kelas_paket > 0){
                        $member->deposit_kelas_paket = $member->deposit_kelas_paket - 1;
                        $member->save();
                        //update utk struk
                        $bookingKelas->jenis_pembayaran_id = 3;
                        $bookingKelas->sisa_deposit = $member->deposit_kelas_paket;
                        $bookingKelas->masa_berlaku_deposit = $member->deactived_deposit_kelas_paket;
                    }else{
                        $member->deposit_reguler -= $kelas->harga;
                        $member->save();
                        //update utk struk
                        $bookingKelas->jenis_pembayaran_id = 2;
                        $bookingKelas->sisa_deposit = $member->deposit_reguler;
                    }

                    $bookingKelas->save();
                }
                $idMember = array_slice($idMember, $randBook);
            }
        }
    }
}
