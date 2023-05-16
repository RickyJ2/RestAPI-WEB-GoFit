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
            'deskripsi' => 'GoFit adalah studio kebugaran eksklusif di Yogyakarta dengan kelas terjadwal dan fasilitas mandiri. Anggota dapat memesan kelas melalui aplikasi, menggunakan alat-alat kebugaran secara mandiri, dan menikmati fasilitas seperti shower air hangat dan handuk. Aplikasi ini memungkinkan anggota mengelola keanggotaan, jadwal kelas, dan pembayaran dengan mudah.',
            'biaya_aktivasi_membership' => 3000000,
            'min_deposit_reguler' => 500000,
            'max_deposit_kelas_awal' => 0,
        ]);
    }
}
