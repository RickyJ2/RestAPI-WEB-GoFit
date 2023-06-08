<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\transaksi;
use App\Models\detail_transaksi_deposit_reguler as detailTransaksiDepositReguler;
use App\Models\detail_transaksi_deposit_kelas_paket as detailTransaksiDepositKelasPaket;
use App\Models\member as Member;

return new class extends Migration
{
    public function createTransaksi(String $member_id, String $jenis_transaksi_id, String $created_at){
        $pegawai_id = 'P0' . rand(4,5);
        if($jenis_transaksi_id != 5){
            DB::table('transaksis')->insert([
                'pegawai_id' => $pegawai_id,
                'member_id' => $member_id,
                'jenis_transaksi_id' => $jenis_transaksi_id,
                'created_at' => $created_at,
            ]);
            $transaksi = Transaksi::where('pegawai_id', $pegawai_id)
                ->where('member_id', $member_id)
                ->where('jenis_transaksi_id', $jenis_transaksi_id)
                ->where('created_at', $created_at)
                ->first();
        }else{
            DB::table('transaksis')->insert([
                'member_id' => $member_id,
                'jenis_transaksi_id' => $jenis_transaksi_id,
                'created_at' => $created_at,
            ]);
            $transaksi = Transaksi::where('member_id', $member_id)
                ->where('jenis_transaksi_id', $jenis_transaksi_id)
                ->where('created_at', $created_at)
                ->first();
        }
        return $transaksi;
    }
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
                SET @next_id = (SELECT IFNULL(
                    MAX(
                        CAST(
                            RIGHT(id, LOCATE(
                                '.', REVERSE(id)
                            ) 
                                  - 1)AS UNSIGNED
                        ) 
                    ), 0
                ) + 1 FROM members);
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
            $table->string('password');
            $table->rememberToken();
            $table->date('deactived_membership_at')->nullable()->default(null);
            $table->integer('deposit_reguler')->default(0);
            $table->integer('deposit_kelas_paket')->default(0);
            $table->date('deactived_deposit_kelas_paket')->nullable()->default(null);
            $table->foreignId('kelas_deposit_kelas_paket_id')->nullable()->default(null)->constrained('kelas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('deleted_at')->nullable()->default(null);
        });

        DB::statement($triggerSQL);
         //member
         $namaMember = ['10589_Ricky Junaidi','Matthew Wilson', 'Noah Young', 'Ava Anderson', 'Daniel Moore', 'Sophia Taylor', 'James Robinson', 'Benjamin Miller', 'Charlotte White', 'Alexander Johnson', 'Michael Clark', 'Olivia Scott', 'Noah Taylor', 'Benjamin Harris', 'John Harris', 'Sophia Wilson', 'Alexander Davis', 'John Miller', 'Emily Martin', 'Matthew Lee', 'Olivia Davis', 'Daniel Wright', 'Emma Young', 'Isabella Robinson', 'Emily Taylor', 'Victoria Davis', 'James Davis', 'James Martin', 'Amelia Smith', 'Ethan Miller', 'Amelia Walker', 'Ethan Davis', 'Jane Wilson', 'Olivia White', 'Isabella Scott', 'Daniel Lewis', 'Noah Smith', 'Emily Lewis', 'Daniel Wilson', 'James Clark', 'Jane Moore', 'Isabella Harris', 'Michael Wilson', 'Sophia Smith', 'Benjamin Martin', 'Alexander Anderson', 'Amelia Davis', 'Victoria Walker', 'John Clark', 'Emma Walker', 'Matthew Lewis', 'Emily Smith', 'Arianna Mcknight','Tommy-Lee Carroll','Zakaria Slater','Ophelia Fisher','Esme Mack','Cleo Buckley','Kyra Barnett','Brianna Sanchez','Honey Lucas','Hugo Ortiz','Mahir Pena','Karina Sheppard','Simeon Archer','Alma Oneal','Susie Connolly','Uzair Shepherd','Ria Thornton','Roisin Sullivan','Isaiah Wang','Darcie Stevenson', ];
         $jalanMember =['Karya Utama', 'Sejati Damai', 'Megah Ria', 'Cendekia', 'Babarsari', 'Centralpark', 'Sinar Kasih', 'Abadi', 'Buntu', 'Klaten', 'Gunung Sahari', 'Kartini', 'Rawamangun', 'Tulip', 'Diponegoro', 'Gading', 'Pluit', 'Cempaka Putih', 'Kemang', 'Menteng', 'Dahlia', 'Sakura', 'Sudirman', 'Cilandak', 'Permata Hijau', 'Senayan', 'Kelapa Gading', 'Anggrek', 'Pegangsaan', 'Tanah Abang', 'Kuningan', 'Taman Sari', 'Cipinang', 'Surya Kencana', 'Menteng', 'Gajah Mada', 'Melati', 'Sunter', 'Puri Indah', 'Tebet', 'Cendrawasih', 'Thamrin', 'Kebon Jeruk', 'Cipete', 'Pahlawan', 'Kalibata', 'Bougenville', 'Cipete', 'Kemayoran', 'Gunung Sahari', 'Gatot Subroto', 'Menteng', 'Merdeka', 'Kamboja', 'Pasar Baru', 'Sudirman', 'Kamboja', 'Pancoran', 'Teratai', 'Seruni', 'Pasar Minggu', 'Flamboyan', 'Cendrawasih', 'Taman Sari',];
         $start_dateBorn = '1990-01-01';
         $end_dateBorn = '2004-12-31';
         $start_dateBorn = new Carbon($start_dateBorn);
         $end_dateBorn = new Carbon($end_dateBorn);
 
         //Akun default 
         DB::table('members')->insert([
             [
                 'nama' => $namaMember[0],
                 'alamat' => 'Jl. ' . $jalanMember[rand(0, count($jalanMember) - 1)] . ' No. ' . rand(1,30)  . ' Yogyakarta',
                 'tgl_lahir' => '2002-06-02',
                 'no_telp' => '08117601123',
                 'email' => '200710589@students.uajy.ac.id',
                 'password' => bcrypt(Carbon::parse('2002-06-02')->format('dmy')),
                 'created_at' => '2023-01-02 00:00:00',
             ],
         ]);

         //generate akun member
         for($id = 1; $id < count($namaMember); $id++){
            $bornDateRand = Carbon::createFromTimestamp(rand($start_dateBorn->timestamp, $end_dateBorn->timestamp));
            $phone_numberRand = '08';
            for ($i = 0; $i < 8; $i++) {
                $phone_numberRand .= rand(0, 9);
            }
            DB::table('members')->insert([
                [
                    'nama' => $namaMember[$id],
                    'alamat' => 'Jl. ' . $jalanMember[rand(0, count($jalanMember) - 1)] . ' No. ' . rand(1,30)  . ' Yogyakarta',
                    'tgl_lahir' => $bornDateRand,
                    'no_telp' => $phone_numberRand,
                    'email' => $namaMember[$id] . '@gmail.com',
                    'password' => bcrypt($bornDateRand->format('dmy')),
                    'created_at' => '2023-01-02 00:00:00',
                ],
            ]);
         }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
