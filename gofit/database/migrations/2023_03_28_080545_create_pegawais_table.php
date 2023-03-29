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
        CREATE TRIGGER pegawais_id_trigger
        BEFORE INSERT ON pegawais
        FOR EACH ROW
        BEGIN
            SET @next_id = (SELECT IFNULL(MAX(RIGHT(id, 2)), 0) + 1 FROM pegawais WHERE LEFT(id, 1) = 'P');
            SET NEW.id = CONCAT('P', LPAD(@next_id, 2, '0'));
        END;
        ";

        Schema::create('pegawais', function (Blueprint $table) {
            $table->string('id',5)->unique()->primary();
            $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nama')->index();
            $table->string('alamat');
            $table->date('tgl_lahir');
            $table->string('no_telp',15);
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->dateTime('quited_at')->nullable()->default(null);

            $table->index(['username', 'password']);
        });

        DB::statement($triggerSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
