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
            CREATE TRIGGER transaksi_no_struk_trigger
            BEFORE INSERT ON transaksis
            FOR EACH ROW
            BEGIN
                DECLARE year_prefix VARCHAR(2);
                DECLARE month_prefix VARCHAR(2);
                SET @next_id = (SELECT IFNULL(MAX(RIGHT(id, LOCATE('.', REVERSE(id)) - 1)), 0) + 1 FROM transaksis);
                SET year_prefix = DATE_FORMAT(NEW.created_at, '%y');
                SET month_prefix = DATE_FORMAT(NEW.created_at, '%m');
                IF( @next_id < 10 ) THEN
                    SET NEW.id = CONCAT(year_prefix, '.', month_prefix, '.', LPAD(@next_id, 2, '0'));
                ELSE
                    SET NEW.id = CONCAT(year_prefix, '.', month_prefix, '.', @next_id);
                END IF;
            END
        ";

        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('pegawai_id')->nullable()->default(null);
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->cascadeOnUpdate()->cascadeOnDelete(); 
            $table->string('member_id');  
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('jenis_transaksi_id')->constrained('jenis_transaksis')->cascadeOnUpdate()->restrictOnDelete();
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
        Schema::dropIfExists('transaksis');
    }
};
