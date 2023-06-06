<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\instruktur as instruktur;
use App\Models\jadwal_umum as jadwalUmum;
use App\Models\izin_instruktur as izinInstruktur;
use Carbon\Carbon;

return new class extends Migration
{
    public function cekJadwalInstruktur(String $jam_mulai, String $hari,int $id){
        $instrukturPeganti = instruktur::find($id);
        if(is_null($instrukturPeganti)){
            return false;
        }
        $jadwalUmum = jadwalUmum::where('instruktur_id', $id)
            ->where('hari', $hari)
            ->where('jam_mulai', '>' ,Carbon::parse($jam_mulai)->subHour()->format('H:i'))
            ->where('jam_mulai', '<' ,Carbon::parse($jam_mulai)->addHour()->format('H:i'))
            ->first();
        $izin_instruktur = izinInstruktur::where('instruktur_penganti_id', $id)
            ->leftJoin('jadwal_umums', 'izin_instrukturs.jadwal_umum_id', '=', 'jadwal_umums.id')    
            ->where('jadwal_umums.hari', $hari)
            ->where('jadwal_umums.jam_mulai', '>' ,Carbon::parse($jam_mulai)->subHour()->format('H:i'))
            ->where('jadwal_umums.jam_mulai', '<' ,Carbon::parse($jam_mulai)->addHour()->format('H:i'))
            ->first();
        if(is_null($jadwalUmum) && is_null($izin_instruktur)){
            return false;
        }else{
            return true;
        }
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('izin_instrukturs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_umum_id')->index()->constrained('jadwal_umums')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('instruktur_pengaju_id')->constrained('instrukturs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('instruktur_penganti_id')->constrained('instrukturs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_izin');
            $table->string('keterangan');
            $table->integer('is_confirmed')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        //Ajukan Izin Instruktur
        $start_date = Carbon::parse('2023-04-03')->startOfWeek(Carbon::SUNDAY)->addDay();
        $end_date = Carbon::now();
        $list_keterangan_izin = ['Ada jadwal mengajar di gym lain', 'Nikahan', 'Sakit', 'Capek', 'Lelah', 'Ada urusan keluarga', 'Ada urusan lain', 'Ada acara', 'Ada rapat', 'Ada tugas', 'Ada kegiatan', 'Ada acara keluarga', 'Ada acara lain', 'Ada tugas kuliah', 'Ada tugas kampus', 'Ada tugas lain', 'Ada tugas kantor'];
        for($date = $start_date; $date <= $end_date; $date->addDays(rand(3, 30))){
            $jadwalUmum = DB::table('jadwal_umums')
                ->where('hari', '=' , $date->format('l'))
                ->get();
            $jadwalUmumRand = $jadwalUmum[rand(0, count($jadwalUmum) - 1)];
            //cara instruktur penganti
            $instruktur_penganti_id = rand(1,12);
            while($this->cekJadwalInstruktur($jadwalUmumRand->jam_mulai, $jadwalUmumRand->hari, $instruktur_penganti_id) && $instruktur_penganti_id != $jadwalUmumRand->instruktur_id){
                $instruktur_penganti_id++;
                if($instruktur_penganti_id > 12){
                    $instruktur_penganti_id = 1;
                }
            }
            if($instruktur_penganti_id > 12){
                continue;
            }

            //random konfirmasi 70% diterima 30% ditolak
            $randConfirmed = rand(1, 10);
            if($randConfirmed < 7) {
                $randConfirmed = 1;
            }else{
                $randConfirmed = 0;
            }

            DB::table('izin_instrukturs')->insert([
                'jadwal_umum_id' => $jadwalUmumRand->id,
                'instruktur_pengaju_id' => $jadwalUmumRand->instruktur_id,
                'instruktur_penganti_id' => $instruktur_penganti_id,
                'tanggal_izin' => $date,
                'keterangan' => $list_keterangan_izin[rand(0, count($list_keterangan_izin) - 1)],
                'is_confirmed' => $randConfirmed,
                'created_at' => $date->copy()->setTime(rand(8, 20), rand(0, 59), rand(0, 59)),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_instrukturs');
    }
};
