<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'teacher_id',
        'score',
        'validated',
    ];

    protected $casts = [
        'validated' => 'boolean',
        'score' => 'float',
    ];

    // Relasi ke tabel students
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke tabel subjects
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Relasi ke tabel users (teacher)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Accessor untuk menghitung grade berdasarkan score
    public function getGradeAttribute()
    {
        if ($this->score >= 85) return 'A';
        if ($this->score >= 80) return 'A-';
        if ($this->score >= 75) return 'B+';
        if ($this->score >= 70) return 'B';
        if ($this->score >= 65) return 'B-';
        if ($this->score >= 60) return 'C+';
        if ($this->score >= 55) return 'C';
        if ($this->score >= 50) return 'C-';
        if ($this->score >= 45) return 'D+';
        if ($this->score >= 40) return 'D';
        return 'E';
    }

    // Accessor untuk mendapatkan warna badge berdasarkan grade
    public function getGradeColorAttribute()
    {
        $grade = $this->grade;
        if (in_array($grade, ['A', 'A-'])) return 'success';
        if (in_array($grade, ['B+', 'B', 'B-'])) return 'warning';
        if (in_array($grade, ['C+', 'C', 'C-'])) return 'info';
        return 'danger';
    }
}
