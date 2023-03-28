<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensi_instrukturs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instruktur_id')->constrained('instrukturs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('jadwal_harian_id')->constrained('jadwal_harians')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('jenis_presensi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_instrukturs');
    }
};
