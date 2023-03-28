<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class jenis_transaksi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_transaksis')->insert([[
            'nama' => 'Aktivasi',
        ],[
            'nama' => 'Deposit Reguler',
        ],[
            'nama' => 'Deposit Kelas',
        ],[
            'nama' => 'Presensi Gym',
        ],[
            'nama' => 'Presensi Kelas',
        ],
        ]);
    }
}
