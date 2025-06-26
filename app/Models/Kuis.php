<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;

   protected $fillable = [
    'pertanyaan',
    'jawaban_a',
    'jawaban_b',
    'jawaban_c',
    'jawaban_d',
    'jawaban_benar',
    'materi_id',
    'waktu_pengerjaan',
    'nilai',
];


    // Relasi ke materi
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    // Relasi ke hasil kuis
    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class);
    }
}
