<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKuis extends Model
{
    protected $table = 'hasil_kuis';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'siswa_id',
        'kuis_id',
        'jawaban_user',
        'benar',
        'waktu',
        'nilai',
    ];

    // Relasi ke model Siswa (banyak hasil kuis milik satu siswa)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke model Kuis (banyak hasil kuis milik satu kuis)
    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }
}
