<?php
use Illuminate\Support\Facades\DB;
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
            $table->foreignId('status_id')->nullable()->default(null)->constrained('status_jadwal_harians')->cascadeOnUpdate()->cascadeOnDelete();
            $table->time('jam_mulai')->nullable()->default(null);
            $table->time('jam_selesai')->nullable()->default(null);
            $table->integer('akumulasi_terlambat')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
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
