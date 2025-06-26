<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('hasil_kuis', function (Blueprint $table) {
            $table->unsignedBigInteger('siswa_id');  // Tambahkan kolom siswa_id
            $table->foreign('siswa_id')->references('id')->on('data_siswa')->onDelete('cascade');  // Menambahkan relasi ke tabel data_siswa
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('hasil_kuis', function (Blueprint $table) {
        $table->dropForeign(['siswa_id']);
        $table->dropColumn('siswa_id');
    });
}

};
