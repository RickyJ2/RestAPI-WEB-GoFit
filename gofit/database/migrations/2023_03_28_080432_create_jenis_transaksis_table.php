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
        Schema::create('jenis_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
        });
        DB::table('jenis_transaksis')->insert([[
            'nama' => 'Aktivasi',
        ],[
            'nama' => 'Deposit Reguler',
        ],[
            'nama' => 'Deposit Kelas',
        ],[
            'nama' => 'Presensi Gym',
        ],[
            'nama' => 'Presensi Kelas',
        ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_transaksis');
    }
};
