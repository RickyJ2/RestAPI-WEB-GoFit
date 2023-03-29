<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            informasi_umum::class,
            jabatan::class,
            jenis_deposit::class,
            jenis_transaksi::class,
            promo::class,
            sesi_gym::class,
            status_jadwal_harian::class,
            dataDummy::class,
        ]);
    }
}
