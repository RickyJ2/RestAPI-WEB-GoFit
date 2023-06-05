<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class jadwal_umum extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Jadwal Umum
        DB::table('jadwal_umums')->insert([[ 
            'kelas_id' => 1,
            'instruktur_id' => 1,
            'hari' => 'Monday',
            'jam_mulai' => '08:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 2,
            'instruktur_id' => 2,
            'hari' => 'Monday',
            'jam_mulai' => '09:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 3,
            'instruktur_id' => 3,
            'hari' => 'Tuesday',
            'jam_mulai' => '08:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 4,
            'instruktur_id' => 4,
            'hari' => 'Tuesday',
            'jam_mulai' => '09:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 5,
            'instruktur_id' => 5,
            'hari' => 'Wednesday',
            'jam_mulai' => '08:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 6,
            'instruktur_id' => 6,
            'hari' => 'Wednesday',
            'jam_mulai' => '08:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 7,
            'instruktur_id' => 7,
            'hari' => 'Wednesday',
            'jam_mulai' => '09:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 8,
            'instruktur_id' => 8,
            'hari' => 'Thursday',
            'jam_mulai' => '08:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 9,
            'instruktur_id' => 9,
            'hari' => 'Thursday',
            'jam_mulai' => '09:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 7,
            'instruktur_id' => 9,
            'hari' => 'Friday',
            'jam_mulai' => '08:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 10,
            'instruktur_id' => 10,
            'hari' => 'Friday',
            'jam_mulai' => '09:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 11,
            'instruktur_id' => 11,
            'hari' => 'Saturday',
            'jam_mulai' => '08:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 12,
            'instruktur_id' => 3,
            'hari' => 'Saturday',
            'jam_mulai' => '09:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 13,
            'instruktur_id' => 2,
            'hari' => 'Saturday',
            'jam_mulai' => '09:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 14,
            'instruktur_id' => 1,
            'hari' => 'Sunday',
            'jam_mulai' => '09:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 10,
            'instruktur_id' => 10,
            'hari' => 'Monday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 15,
            'instruktur_id' => 4,
            'hari' => 'Monday',
            'jam_mulai' => '18:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 14,
            'instruktur_id' => 1,
            'hari' => 'Tuesday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 1,
            'instruktur_id' => 7,
            'hari' => 'Tuesday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 16,
            'instruktur_id' => 8,
            'hari' => 'Tuesday',
            'jam_mulai' => '18:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 17,
            'instruktur_id' => 12,
            'hari' => 'Wednesday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 9,
            'instruktur_id' => 9,
            'hari' => 'Wednesday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 11,
            'instruktur_id' => 11,
            'hari' => 'Wednesday',
            'jam_mulai' => '18:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 18,
            'instruktur_id' => 6,
            'hari' => 'Thursday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 5,
            'instruktur_id' => 5,
            'hari' => 'Thursday',
            'jam_mulai' => '18:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 7,
            'instruktur_id' => 7,
            'hari' => 'Friday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 2,
            'instruktur_id' => 2,
            'hari' => 'Friday',
            'jam_mulai' => '18:30',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 19,
            'instruktur_id' => 12,
            'hari' => 'Saturday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 11,
            'instruktur_id' => 11,
            'hari' => 'Saturday',
            'jam_mulai' => '17:00',
            'created_at' => '2022-01-01 00:00:00',
        ],[ 
            'kelas_id' => 16,
            'instruktur_id' => 8,
            'hari' => 'Saturday',
            'jam_mulai' => '18:30',
            'created_at' => '2022-01-01 00:00:00',
        ],
    ]);
    }
}
