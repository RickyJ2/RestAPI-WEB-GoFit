<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class jenis_deposit extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_deposits')->insert([[
            'nama' => 'Reguler',
        ],[
            'nama' => 'Kelas Paket',
        ],
        ]);
    }
}
