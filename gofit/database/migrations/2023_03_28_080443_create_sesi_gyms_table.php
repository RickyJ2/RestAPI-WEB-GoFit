<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sesi_gyms', function (Blueprint $table) {
            $table->id();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
        });
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_gyms');
    }
};
