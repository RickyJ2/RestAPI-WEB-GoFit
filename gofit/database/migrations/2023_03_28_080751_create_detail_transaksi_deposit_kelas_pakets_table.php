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
        Schema::create('detail_transaksi_deposit_kelas_pakets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('no_nota')->constrained('transaksis')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('promo_id')->constrained('promos')->cascadeOnUpdate()->cascadeOnDelete()->default(null);
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('nominal');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_deposit_kelas_pakets');
    }
};
