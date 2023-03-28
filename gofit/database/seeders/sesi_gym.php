<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class sesi_gym extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sesi_gyms')->insert([[
            'jam_mulai' => '07:00',
            'jam_selesai' => '09:00',
        ],[
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
        ],[
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
        ],[
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:00',
        ],[
            'jam_mulai' => '15:00',
            'jam_selesai' => '17:00',
        ],[
            'jam_mulai' => '17:00',
            'jam_selesai' => '19:00',
        ],[
            'jam_mulai' => '19:00',
            'jam_selesai' => '21:00',
        ],
        ]);
    }
}
