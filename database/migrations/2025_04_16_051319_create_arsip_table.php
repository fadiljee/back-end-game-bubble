<?php

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
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->unsignedBigInteger('id_lokasi');
            $table->unsignedBigInteger('id_user');
            $table->string('file_arsip');
            $table->timestamps();
            $table->foreign('id_lokasi')->references('id')->on('lokasi')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('data_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
