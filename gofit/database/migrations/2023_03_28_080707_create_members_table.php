<?php

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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nama')->index();
            $table->string('alamat');
            $table->date('tgl_lahir');
            $table->string('no_telp');
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->date('deactived_membership_at')->default(null);
            $table->integer('deposit_reguler')->default(0);
            $table->integer('deposit_kelas_paket')->default(0);
            $table->date('deactived_deposit_kelas_paket')->default(null);
            $table->foreignId('kelas_deposit_kelas_paket_id')->constrained('kelas')->cascadeOnUpdate()->cascadeOnDelete()->default(null);
            $table->timestamps();
            $table->dateTime('quited_at')->default(null);

            $table->index(['username', 'password']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
