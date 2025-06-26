<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('hasil_kuis', function (Blueprint $table) {
        $table->boolean('benar')->default(false);  // Menambahkan kolom benar dengan default false
    });
}

public function down()
{
    Schema::table('hasil_kuis', function (Blueprint $table) {
        $table->dropColumn('benar');
    });
}

};
