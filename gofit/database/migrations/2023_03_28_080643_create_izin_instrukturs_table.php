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
        Schema::create('izin_instrukturs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_umum_id')->index()->constrained('jadwal_umums')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('instruktur_pengaju_id')->constrained('instrukturs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('instruktur_penganti_id')->constrained('instrukturs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_izin');
            $table->string('keterangan');
            $table->integer('is_confirmed')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_instrukturs');
    }
};
