<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
// GANTI baris di bawah ini
use Illuminate\Foundation\Auth\User as Authenticatable;

// GANTI baris di bawah ini juga
class Siswa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // 'HasFactory' bisa dipindah ke sini

    protected $table = 'data_siswa';

    protected $fillable = [
        'nisn',
        'nama',
    ];

    // Sisa kode (relasi, dll) biarkan seperti adanya
    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class);
    }
}
