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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_promo_id')->constrained('jenis_deposits')->restrictOnUpdate()->restrictOnDelete();
            $table->integer('min_deposit_member');
            $table->integer('max_deposit_member');
            $table->integer('kriteria_pembelian');
            $table->integer('bonus');
            $table->integer('masa_berlaku');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
