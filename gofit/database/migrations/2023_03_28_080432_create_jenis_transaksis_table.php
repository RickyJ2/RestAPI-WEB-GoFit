<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('db:seed', [
            '--class' => \Database\Seeders\jenis_transaksi::class,
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
