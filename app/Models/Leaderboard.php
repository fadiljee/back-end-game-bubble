<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    /**
     * Opsional tapi direkomendasikan:
     * Mendefinisikan nama tabel secara eksplisit.
     */
    protected $table = 'leaderboard';

    /**
     * Kolom yang boleh diisi secara massal.
     * PENTING: Tambahkan 'siswa_id' di sini.
     */
    protected $fillable = [
        'siswa_id', // <-- TAMBAHKAN INI
        'time',     // waktu pengerjaan dalam detik
    ];

    /**
     * Mengubah tipe data kolom saat diakses.
     */
    protected $casts = [
        'time' => 'integer',
    ];

    /**
     * Relasi ke model Siswa. Bagian ini sudah benar.
     */
    public function Siswa()
    {
        // Ganti Siswa::class dengan model user Anda jika namanya berbeda
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
