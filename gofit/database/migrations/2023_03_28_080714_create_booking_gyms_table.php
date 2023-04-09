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
        Schema::create('booking_gyms', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota');  
            $table->foreign('no_nota')->nullable()->default(null)->references('id')->on('transaksis')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('member_id');  
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sesi_gym_id')->constrained('sesi_gyms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tgl_booking');
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
        Schema::dropIfExists('booking_gyms');
    }
};
