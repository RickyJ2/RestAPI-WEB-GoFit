<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class promo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promos')->insert([[
            'jenis_promo_id' => 2,
            'kriteria_pembelian' => 3000000,
            'bonus' => 300000,
            'masa_berlaku' => null,
        ],[
            'jenis_promo_id' => 3,
            'kriteria_pembelian' => 5,
            'bonus' => 1,
            'masa_berlaku' => 1,
        ],[
            'jenis_promo_id' => 3,
            'kriteria_pembelian' => 10,
            'bonus' => 3,
            'masa_berlaku' => 2,
        ],
        ]);
    }
}
