<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaderboardTable extends Migration // Nama class mungkin sedikit berbeda
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaderboard', function (Blueprint $table) {
            $table->id(); // Kolom ID (Primary Key)

            // Kolom untuk menghubungkan ke tabel 'siswa'
            // Ganti 'siswa' jika nama tabel user Anda berbeda
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');

            // Kolom untuk menyimpan waktu pengerjaan dalam detik
            $table->integer('time');

            $table->timestamps(); // Kolom created_at dan updated_at

            // Opsional tapi sangat direkomendasikan:
            // Pastikan satu siswa hanya punya satu entri skor
            $table->unique(['siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaderboard');
    }
}
