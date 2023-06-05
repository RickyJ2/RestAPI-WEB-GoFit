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
        Schema::create('status_jadwal_harians', function (Blueprint $table) {
            $table->id();
            $table->String('jenis_status');
        });
        Artisan::call('db:seed', [
            '--class' => \Database\Seeders\status_jadwal_harian::class,
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
