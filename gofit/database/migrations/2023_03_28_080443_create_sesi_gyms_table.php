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
        Schema::create('sesi_gyms', function (Blueprint $table) {
            $table->id();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
        });
        Artisan::call('db:seed', [
            '--class' => \Database\Seeders\sesi_gym::class,
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
