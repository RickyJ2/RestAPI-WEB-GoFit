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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_promo_id')->constrained('jenis_transaksis')->restrictOnUpdate()->restrictOnDelete();
            $table->integer('kriteria_pembelian');
            $table->integer('bonus');
            $table->integer('masa_berlaku')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
        DB::table('promos')->insert([[
            'jenis_promo_id' => 2,
            'kriteria_pembelian' => 3000000,
            'bonus' => 300000,
            'masa_berlaku' => null,
        ],[
            'jenis_promo_id' => 3,
            'kriteria_pembelian' => 5,
            'bonus' => 1,
            'masa_berlaku' => 1,
        ],[
            'jenis_promo_id' => 3,
            'kriteria_pembelian' => 10,
            'bonus' => 3,
            'masa_berlaku' => 2,
        ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
