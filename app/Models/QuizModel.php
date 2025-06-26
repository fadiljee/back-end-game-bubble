<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizModel extends Model
{
    protected $table = 'quiz';
    protected $primaryKey = 'id';
    
     // Relasi ke Materi
     public function materi()
     {
         return $this->belongsTo(Materi::class, 'materi_id', 'id');
     }
}
