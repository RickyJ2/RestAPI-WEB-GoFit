<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class member_seeder extends Seeder
{
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
        }
    }
}
