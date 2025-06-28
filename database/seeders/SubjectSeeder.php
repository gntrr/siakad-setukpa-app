<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['code' => 'AGM101', 'name' => 'Agama'],
            ['code' => 'PKN101', 'name' => 'Pancasila'],
            ['code' => 'PWK101', 'name' => 'Pendidikan Kewarganegaraan'],
            ['code' => 'BIN101', 'name' => 'Bahasa Indonesia'],
            ['code' => 'KAR101', 'name' => 'Pembentukan Karakter'],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
    }
}
