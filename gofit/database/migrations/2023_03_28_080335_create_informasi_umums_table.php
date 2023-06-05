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
        Schema::create('informasi_umums', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat');
            $table->text('deskripsi');
            $table->integer('biaya_aktivasi_membership');
            $table->integer('min_deposit_reguler');
            $table->integer('max_deposit_kelas_awal');
        });
        // Artisan::call('db:seed', [
        //     '--class' => \Database\Seeders\informasi_umum::class,
        // ]);
        DB::table('informasi_umums')->insert([
            'nama' => 'GoFit',
            'alamat' => 'Jl. Centralpark No. 10 Yogyakarta',
            'deskripsi' => 'GoFit adalah studio kebugaran eksklusif di Yogyakarta dengan kelas terjadwal dan fasilitas mandiri. Anggota dapat memesan kelas melalui aplikasi, menggunakan alat-alat kebugaran secara mandiri, dan menikmati fasilitas seperti shower air hangat dan handuk. Aplikasi ini memungkinkan anggota mengelola keanggotaan, jadwal kelas, dan pembayaran dengan mudah.',
            'biaya_aktivasi_membership' => 3000000,
            'min_deposit_reguler' => 500000,
            'max_deposit_kelas_awal' => 0,
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
