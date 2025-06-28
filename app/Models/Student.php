<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'name',
        'gender',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relasi ke tabel scores
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
