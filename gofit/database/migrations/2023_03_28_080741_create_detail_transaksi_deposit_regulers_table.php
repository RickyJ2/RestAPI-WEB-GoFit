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
        Schema::create('detail_transaksi_deposit_regulers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('no_nota')->constrained('transaksis')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('promo_id')->nullable()->default(null)->constrained('promos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('nominal');
            $table->integer('total');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_deposit_regulers');
    }
};