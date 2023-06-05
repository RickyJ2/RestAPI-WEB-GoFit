<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class pegawai extends Seeder
{
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
                'created_at' => '2022-01-01 00:00:00',
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
    }
}
