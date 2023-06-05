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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('harga');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
        DB::table('kelas')->insert([[
            'nama' => 'SPINE Corrector',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'MUAYTHAI',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'PILATES',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'ASTHANGA',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Body Combat',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'ZUMBA',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'HATHA',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Wall Swing',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Basic Swing',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Bellydance',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'BUNGEE',
            'harga' => 200000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Yogalates',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'BOXING',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Calisthenics',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Pound Fit',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Trampoline Workout',
            'harga' => 200000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Yoga For Kids',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Abs Pilates',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Swing For Kids',
            'harga' => 150000,
            'created_at' => '2022-01-01 00:00:00',
        ],
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
