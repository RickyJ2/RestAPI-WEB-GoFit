<?php
use Illuminate\Support\Facades\DB;
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
             
        $triggerSQL = "
            CREATE TRIGGER pegawais_id_trigger
            BEFORE INSERT ON pegawais
            FOR EACH ROW
            BEGIN
                SET @next_id = (SELECT IFNULL(MAX(RIGHT(id, LOCATE('P', REVERSE(id)) - 1)), 0) + 1 FROM pegawais);
                IF( @next_id < 10 ) THEN
                    SET NEW.id = CONCAT('P', LPAD(@next_id, 2, '0'));
                ELSE
                    SET NEW.id = CONCAT('P', @next_id);
                END IF;
            END
        ";

        Schema::create('pegawais', function (Blueprint $table) {
            $table->string('id',5)->unique()->primary();
            $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nama')->index();
            $table->string('alamat');
            $table->date('tgl_lahir');
            $table->string('no_telp',15);
            $table->string('username')->unique()->nullable()->default(null);
            $table->string('password')->nullable()->default(null);
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index(['username', 'password']);
        });

        DB::statement($triggerSQL);
        Artisan::call('db:seed', [
            '--class' => \Database\Seeders\pegawai::class,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
