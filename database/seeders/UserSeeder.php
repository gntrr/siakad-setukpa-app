<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@setukpa.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Dosen
        \App\Models\User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi@setukpa.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        \App\Models\User::create([
            'name' => 'Prof. Siti Aminah',
            'email' => 'siti@setukpa.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        // Manajemen
        \App\Models\User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad@setukpa.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'manajemen',
        ]);

        // Siswa
        \App\Models\User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi.siswa@setukpa.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
        ]);

        \App\Models\User::create([
            'name' => 'Maya Sari',
            'email' => 'maya.siswa@setukpa.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
        ]);
    }
}
