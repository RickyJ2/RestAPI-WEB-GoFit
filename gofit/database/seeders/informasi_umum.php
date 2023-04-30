<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class informasi_umum extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('informasi_umums')->insert([
            'nama' => 'GoFit',
            'alamat' => 'Jl. Centralpark No. 10 Yogyakarta',
            'deskripsi' => 'c',
            'biaya_aktivasi_membership' => 3000000,
            'min_deposit_reguler' => 500000,
            'max_deposit_kelas_awal' => 0,
        ]);
    }
}
