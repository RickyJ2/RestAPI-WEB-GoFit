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
        $triggerSQL ="
            CREATE TRIGGER transaksi_no_struk_trigger
            BEFORE INSERT ON transaksis
            FOR EACH ROW
            BEGIN
                DECLARE last_id INT; 
                DECLARE new_id VARCHAR(255); 
                SET last_id = ( 
                    SELECT MAX(RIGHT(id, 3)) 
                    FROM transaksis ); 
                IF last_id IS NULL THEN 
                    SET new_id = CONCAT(DATE_FORMAT(NOW(), '%y.%m.'), '001'); 
                ELSE 
                    SET new_id = CONCAT(DATE_FORMAT(NOW(), '%y.%m.'), LPAD(last_id + 1, 3, '0')); 
                END IF; 
                SET NEW.id = new_id; 
            END;
        ";

        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('pegawai_id');
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
