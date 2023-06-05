<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->String('nama');
            $table->integer('level_otoritas');
        });

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
