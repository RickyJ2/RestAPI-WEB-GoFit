<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class jabatan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jabatans')->insert([[
            'nama' => 'Manajer Operasional',
            'level_otoritas' => 1,
        ],[
            'nama' => 'Admin',
            'level_otoritas' => 2,
        ],[
            'nama' => 'Kasir',
            'level_otoritas' => 3,
        ],[
            'nama' => 'Pegawai Biasa',
            'level_otoritas' => 4,
        ],
        ]);
    }
}
