<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $students = Student::all();
        $subjects = Subject::all();
        $teacher = User::where('role', 'admin')->first() ?? User::first();

        if ($students->count() > 0 && $subjects->count() > 0 && $teacher) {
            // Create scores for each student and subject combination
            foreach ($students as $student) {
                foreach ($subjects->take(3) as $subject) { // Limit to 3 subjects per student
                    Score::firstOrCreate([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $teacher->id,
                    ], [
                        'score' => rand(60, 95),
                        'validated' => true,
                    ]);
                }
            }
        }
    }
}
