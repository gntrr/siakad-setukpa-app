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
        'email',
        'gender',
        'birth_date',
        'phone',
        'status',
        'address',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'aktif',
    ];

    // Relasi ke tabel scores
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
