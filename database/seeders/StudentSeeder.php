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
                'email' => 'andi.permana@setukpa.ac.id',
                'gender' => 'Laki-laki',
                'birth_date' => '2006-03-15',
                'phone' => '081234567801',
                'status' => 'Aktif',
                'address' => 'Jl. Merdeka No. 10, Jakarta Pusat'
            ],
            [
                'student_number' => 'STK2024002',
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@setukpa.ac.id',
                'gender' => 'Perempuan',
                'birth_date' => '2006-07-22',
                'phone' => '081234567802',
                'status' => 'Aktif',
                'address' => 'Jl. Sudirman No. 25, Jakarta Selatan'
            ],
            [
                'student_number' => 'STK2024003',
                'name' => 'Rudi Hartono',
                'email' => 'rudi.hartono@setukpa.ac.id',
                'gender' => 'Laki-laki',
                'birth_date' => '2006-01-10',
                'phone' => '081234567803',
                'status' => 'Aktif',
                'address' => 'Jl. Gatot Subroto No. 15, Jakarta Timur'
            ],
            [
                'student_number' => 'STK2024004',
                'name' => 'Maya Sari',
                'email' => 'maya.sari@setukpa.ac.id',
                'gender' => 'Perempuan',
                'birth_date' => '2006-09-05',
                'phone' => '081234567804',
                'status' => 'Aktif',
                'address' => 'Jl. Thamrin No. 30, Jakarta Pusat'
            ],
            [
                'student_number' => 'STK2024005',
                'name' => 'Doni Prasetyo',
                'email' => 'doni.prasetyo@setukpa.ac.id',
                'gender' => 'Laki-laki',
                'birth_date' => '2006-11-18',
                'phone' => '081234567805',
                'status' => 'Aktif',
                'address' => 'Jl. HR Rasuna Said No. 12, Jakarta Selatan'
            ],
        ];

        foreach ($students as $student) {
            \App\Models\Student::create($student);
        }
    }
}
