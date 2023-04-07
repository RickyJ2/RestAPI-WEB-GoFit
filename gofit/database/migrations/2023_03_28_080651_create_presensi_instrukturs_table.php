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
        $triggerSQL = "
            CREATE TRIGGER update_akumulasi_terlambat
            AFTER INSERT ON presensi_instrukturs
            FOR EACH ROW
            BEGIN
                DECLARE jam_masuk TIMESTAMP;
                SET jam_masuk = (SELECT jadwal_umums.jam_mulai FROM jadwal_harians 
                    JOIN jadwal_umums ON jadwal_harians.jadwal_umum_id = jadwal_umums.id
                    WHERE jadwal_harians.id = NEW.jadwal_harian_id);
                IF NEW.jenis_presensi = 'masuk' AND NEW.created_at > jam_masuk THEN
                    UPDATE instrukturs SET akumulasi_terlambat = akumulasi_terlambat + (NEW.created_at - jam_masuk)
                        WHERE id = NEW.instruktur_id;
                END IF;
            END
        ";
        Schema::create('presensi_instrukturs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instruktur_id')->constrained('instrukturs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('jadwal_harian_id')->constrained('jadwal_harians')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('jenis_presensi',['masuk', 'selesai']);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
        DB::statement($triggerSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_instrukturs');
    }
};
