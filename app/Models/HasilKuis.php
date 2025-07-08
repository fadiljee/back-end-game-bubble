<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKuis extends Model
{
    protected $table = 'hasil_kuis';

    protected $fillable = [
        'siswa_id',
        'kuis_id',
        'jawaban_user',
        'benar',
        'waktu',
        'nilai',
        'attempt_id',   // tambahkan ini
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }
}
