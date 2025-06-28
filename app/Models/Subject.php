<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    // Relasi ke tabel scores
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
