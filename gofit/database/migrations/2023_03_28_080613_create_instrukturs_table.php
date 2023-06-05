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
        Schema::create('instrukturs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->index();
            $table->string('alamat');
            $table->date('tgl_lahir');
            $table->string('no_telp');
            $table->integer('akumulasi_terlambat')->default(0);
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('deleted_at')->nullable()->default(null);
            
            $table->index(['username', 'password']);
        });
        DB::table('instrukturs')->insert([[
            'nama' => 'Joon',
            'alamat' => 'Jl. Babarsari No. 11 Yogyakarta',
            'tgl_lahir' => '1985-05-12',
            'no_telp' => '081928111',
            'username' => 'Joon',
            'password' => bcrypt('120585'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'JK',
            'alamat' => 'Jl. Mahoni No. 08 Yogyakarta',
            'tgl_lahir' => '1992-04-12',
            'no_telp' => '081399991234',
            'username' => 'JK',
            'password' => bcrypt('120492'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Lisa',
            'alamat' => 'Jl. Sosko No. 121 Yogyakarta',
            'tgl_lahir' => '1993-09-01',
            'no_telp' => '081234125689',
            'username' => 'Lisa',
            'password' => bcrypt('010993'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Hobby',
            'alamat' => 'Jl. Bungur No. 123 Yogyakarta',
            'tgl_lahir' => '1992-09-05',
            'no_telp' => '089878123189',
            'username' => 'Hobby',
            'password' => bcrypt('050992'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'V',
            'alamat' => 'Jl. Amartha I No. 97 Yogyakarta',
            'tgl_lahir' => '1988-05-20',
            'no_telp' => '081340901010',
            'username' => 'V',
            'password' => bcrypt('200588'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Jenny',
            'alamat' => 'Jl. Komodo No. 21 Yogyakarta',
            'tgl_lahir' => '1996-07-24',
            'no_telp' => '089145678912',
            'username' => 'Jenny',
            'password' => bcrypt('240796'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Suga',
            'alamat' => 'Jl. Bunga No. 11 Yogyakarta',
            'tgl_lahir' => '1996-04-15',
            'no_telp' => '081134128756',
            'username' => 'Suga',
            'password' => bcrypt('150496'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Rose',
            'alamat' => 'Jl. Cempaka No. 03 Yogyakarta',
            'tgl_lahir' => '1994-03-12',
            'no_telp' => '08124290122',
            'username' => 'Rose',
            'password' => bcrypt('120394'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Jin',
            'alamat' => 'Jl. Kerucut No. 95 Yogyakarta',
            'tgl_lahir' => '1995-11-01',
            'no_telp' => '08112341901',
            'username' => 'Jin',
            'password' => bcrypt('011195'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Jisoo',
            'alamat' => 'Jl. Kinanti No. 16 Yogyakarta',
            'tgl_lahir' => '1994-10-20',
            'no_telp' => '0891234121',
            'username' => 'Jisoo',
            'password' => bcrypt('201094'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Jimin',
            'alamat' => 'Jl. Kaliurang Barat No. 32 Yogyakarta',
            'tgl_lahir' => '1990-02-16',
            'no_telp' => '0819021312',
            'username' => 'Jimin',
            'password' => bcrypt('160290'),
            'created_at' => '2022-01-01 00:00:00',
        ],[
            'nama' => 'Jessi',
            'alamat' => 'Jl. Apel No. 01 Yogyakarta',
            'tgl_lahir' => '1993-02-10',
            'no_telp' => '081341009111',
            'username' => 'Jessi',
            'password' => bcrypt('100293'),
            'created_at' => '2022-01-01 00:00:00',
        ],
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrukturs');
    }
};
