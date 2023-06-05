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
        Schema::create('informasi_umums', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat');
            $table->text('deskripsi');
            $table->integer('biaya_aktivasi_membership');
            $table->integer('min_deposit_reguler');
            $table->integer('max_deposit_kelas_awal');
        });
        Artisan::call('db:seed', [
            '--class' => \Database\Seeders\informasi_umum::class,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_umums');
    }
};
