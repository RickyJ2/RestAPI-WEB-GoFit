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
        Schema::create('status_jadwal_harians', function (Blueprint $table) {
            $table->id();
            $table->String('jenis_status');
        });
        DB::table('status_jadwal_harians')->insert([[
            'jenis_status' => 'libur',
        ],[
            'jenis_status' => 'menggantikan',
        ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_jadwal_harians');
    }
};
