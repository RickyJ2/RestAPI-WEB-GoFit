<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class status_jadwal_harian extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_jadwal_harians')->insert([[
            'jenis_status' => 'libur',
        ],[
            'jenis_status' => 'digantikan',
        ],
        ]);
    }
}
