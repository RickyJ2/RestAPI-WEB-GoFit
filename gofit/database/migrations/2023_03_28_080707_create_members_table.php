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
            CREATE TRIGGER members_id_trigger
            BEFORE INSERT ON members
            FOR EACH ROW
            BEGIN
                DECLARE year_prefix VARCHAR(2);
                DECLARE month_prefix VARCHAR(2);
                SET @next_id = (SELECT IFNULL(MAX(RIGHT(id, LOCATE('.', REVERSE(id)) - 1)), 0) + 1 FROM members);
                SET year_prefix = DATE_FORMAT(NEW.created_at, '%y');
                SET month_prefix = DATE_FORMAT(NEW.created_at, '%m');
                IF( @next_id < 10 ) THEN
                    SET NEW.id = CONCAT(year_prefix, '.', month_prefix, '.', LPAD(@next_id, 2, '0'));
                ELSE
                    SET NEW.id = CONCAT(year_prefix, '.', month_prefix, '.', @next_id);
                END IF;
            END
            ";

        Schema::create('members', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('nama')->index();
            $table->string('alamat');
            $table->date('tgl_lahir');
            $table->string('no_telp');
            $table->string('email');
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->date('deactived_membership_at')->nullable()->default(null);
            $table->integer('deposit_reguler')->default(0);
            $table->integer('deposit_kelas_paket')->default(0);
            $table->date('deactived_deposit_kelas_paket')->nullable()->default(null);
            $table->foreignId('kelas_deposit_kelas_paket_id')->nullable()->default(null)->constrained('kelas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index(['username', 'password']);
        });

        DB::statement($triggerSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
