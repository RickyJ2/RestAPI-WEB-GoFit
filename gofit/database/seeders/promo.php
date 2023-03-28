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
            'jenis_promo_id' => 1,
            'min_deposit_member' => 500000,
            'max_deposit_member' => null,
            'kriteria_pembelian' => 3000000,
            'bonus' => 300000,
            'masa_berlaku' => null,
        ],[
            'jenis_promo_id' => 2,
            'min_deposit_member' => null,
            'max_deposit_member' => 0,
            'kriteria_pembelian' => 5,
            'bonus' => 1,
            'masa_berlaku' => 1,
        ],[
            'jenis_promo_id' => 2,
            'min_deposit_member' => null,
            'max_deposit_member' => 0,
            'kriteria_pembelian' => 10,
            'bonus' => 3,
            'masa_berlaku' => 2,
        ],
        ]);
    }
}
