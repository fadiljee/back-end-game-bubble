<?php

namespace App\Models;
use App\Models\Kuis;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuizModel;

class Materi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // === PERUBAHAN: Tambahkan 'link_yt' ke dalam fillable ===
    protected $fillable = ['judul', 'gambar', 'konten', 'link_yt'];


    /**
     * Get the kuis for the materi.
     */
    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }
}
