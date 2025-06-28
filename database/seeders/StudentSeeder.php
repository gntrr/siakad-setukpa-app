<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'student_number' => 'STK2024001',
                'name' => 'Andi Permana',
                'gender' => 'Laki-laki',
                'birth_date' => '2006-03-15'
            ],
            [
                'student_number' => 'STK2024002',
                'name' => 'Sari Dewi',
                'gender' => 'Perempuan',
                'birth_date' => '2006-07-22'
            ],
            [
                'student_number' => 'STK2024003',
                'name' => 'Rudi Hartono',
                'gender' => 'Laki-laki',
                'birth_date' => '2006-01-10'
            ],
            [
                'student_number' => 'STK2024004',
                'name' => 'Maya Sari',
                'gender' => 'Perempuan',
                'birth_date' => '2006-09-05'
            ],
            [
                'student_number' => 'STK2024005',
                'name' => 'Doni Prasetyo',
                'gender' => 'Laki-laki',
                'birth_date' => '2006-11-18'
            ],
        ];

        foreach ($students as $student) {
            \App\Models\Student::create($student);
        }
    }
}
