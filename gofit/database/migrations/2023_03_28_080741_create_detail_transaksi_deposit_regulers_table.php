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
            $table->string('no_nota');  
            $table->foreign('no_nota')->nullable()->default(null)->references('id')->on('transaksis')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('promo_id')->nullable()->default(null)->constrained('promos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('nominal');
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
