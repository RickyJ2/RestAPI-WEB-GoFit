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
        Schema::create('booking_gyms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('no_nota')->constrained('transaksis')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sesi_gym_id')->constrained('sesi_gyms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tgl_booking');
            $table->boolean('presensi')->default(false);
            $table->dateTime('tgl_presensi')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_gyms');
    }
};
