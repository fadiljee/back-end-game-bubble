<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
   public function up()
{
    Schema::table('hasil_kuis', function (Blueprint $table) {
        $table->unsignedBigInteger('kuis_id');
        $table->foreign('kuis_id')->references('id')->on('kuis')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('hasil_kuis', function (Blueprint $table) {
        $table->dropForeign(['kuis_id']);
        $table->dropColumn('kuis_id');
    });
}

};
