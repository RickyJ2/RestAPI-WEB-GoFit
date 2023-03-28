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
        Schema::create('jadwal_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_umum_id')->constrained('jadwal_umums')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal');
            $table->foreignId('status_id')->constrained('status_jadwal_harians')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_harians');
    }
};
