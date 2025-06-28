<?php

use Illuminate\Support\Facades\Route;
use App\Models\Student;

// Test route to check student data
Route::get('/test-student-data/{id}', function($id) {
    $student = Student::findOrFail($id);
    
    return response()->json([
        'id' => $student->id,
        'student_number' => $student->student_number,
        'name' => $student->name,
        'gender' => $student->gender,
        'birth_date' => $student->birth_date,
        'birth_date_formatted' => $student->birth_date ? $student->birth_date->format('Y-m-d') : null,
        'created_at' => $student->created_at,
        'updated_at' => $student->updated_at,
    ]);
});
