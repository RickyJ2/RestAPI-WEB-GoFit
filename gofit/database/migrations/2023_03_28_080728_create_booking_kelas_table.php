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
        Schema::create('booking_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota')->nullable()->default(null);  
            $table->foreign('no_nota')->references('id')->on('transaksis')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('member_id');  
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('jadwal_harian_id')->constrained('jadwal_harians')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('jenis_pembayaran_id')->nullable()->default(null)->constrained('jenis_transaksis')->restrictOnUpdate()->restrictOnDelete();
            $table->integer('sisa_deposit')->default(0);
            $table->timestamp('masa_berlaku_deposit')->nullable()->default(null);
            $table->timestamp('present_at')->nullable()->default(null);
            $table->boolean('is_cancelled')->default(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_kelas');
    }
};
