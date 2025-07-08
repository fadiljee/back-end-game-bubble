<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('hasil_kuis', function (Blueprint $table) {
            $table->string('attempt_id')->after('siswa_id')->index();
        });
    }
    public function down(): void {
        Schema::table('hasil_kuis', function (Blueprint $table) {
            $table->dropColumn('attempt_id');
        });
    }
};