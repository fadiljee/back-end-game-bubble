<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::table('hasil_kuis', function (Blueprint $table) {
            $table->integer('waktu')->default(0)->change();  // Mengubah default value menjadi null atau nilai lain
        });
    }

    public function down()
    {
        Schema::table('hasil_kuis', function (Blueprint $table) {
            $table->integer('waktu')->default(0)->change();  // Kembalikan default value ke 0
        });
    }


};
