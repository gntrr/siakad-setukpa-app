<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if this is an API request
        if ($this->isApiRequest($request)) {
            $query = Student::query();

            // Search functionality
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('student_number', 'like', "%{$search}%");
                });
            }

            // Filter by gender
            if ($request->has('gender') && $request->gender !== '') {
                $query->where('gender', $request->gender);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Include scores count for performance optimization
            $query->withCount('scores');

            // Pagination
            $perPage = $request->get('per_page', 15);
            $students = $query->paginate($perPage);

            return $this->apiSuccess($students, 'Students retrieved successfully');
        }

        // For web requests, get students with pagination
        $search = $request->get('search');
        $gender = $request->get('gender');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query = Student::withCount('scores');

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($gender) {
            $query->where('gender', $gender);
        }

        // Sorting
        $query->orderBy($sortBy, $sortOrder);

        $students = $query->paginate(15);
        
        return view('students.index', compact('students', 'search', 'gender', 'sortBy', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $student = Student::create($request->validated());

            DB::commit();

            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiSuccess($student, 'Student created successfully', 201);
            }

            // Web request - redirect with success message
            return redirect()->route('students.index')->with('success', 'Student created successfully');

        } catch (\Exception $e) {
            Log::error('Failed to create student: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollBack();
            
            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiError('Failed to create student', ['error' => $e->getMessage()], 500);
            }

            // Web request - redirect with error message
            return redirect()->back()->withInput()->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        if ($this->isApiRequest()) {
            // Load related scores with subject and teacher information
            $student->load(['scores.subject', 'scores.teacher:id,name']);

            return $this->apiSuccess($student, 'Student retrieved successfully');
        }

        // Load related scores with subject and teacher information
        $student->load(['scores.subject', 'scores.teacher:id,name']);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, Student $student)
    {
        try {
            $this->authorize('update', $student);
        } catch (\Exception $e) {
            Log::error('Authorization failed for student update: ' . $e->getMessage());
            
            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiError('Tidak memiliki izin untuk mengupdate data siswa', ['error' => $e->getMessage()], 403);
            }
            
            return redirect()->back()->with('error', 'Tidak memiliki izin untuk mengupdate data siswa');
        }

        try {
            DB::beginTransaction();

            Log::info('Updating student with data: ', $request->validated());
            
            $student->update($request->validated());

            DB::commit();

            Log::info('Student updated successfully: ' . $student->id);

            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiSuccess($student->fresh(), 'Student updated successfully');
            }

            // For regular web requests, redirect
            return redirect()->route('students.index')->with('success', 'Student updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update student: ' . $e->getMessage(), [
                'student_id' => $student->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiError('Failed to update student', ['error' => $e->getMessage()], 500);
            }

            // For regular web requests, redirect with error
            return redirect()->back()->withInput()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();

            // Check if student has scores
            if ($student->scores()->exists()) {
                DB::rollBack();
                
                // Check if this is an API request
                if ($this->isApiRequest(request())) {
                    return $this->apiError('Cannot delete student with existing scores. Please delete scores first.', [], 422);
                }

                // Web request error handling
                return redirect()->back()->with('error', 'Cannot delete student with existing scores. Please delete scores first.');
            }

            $student->delete();

            DB::commit();

            // Check if this is an API request
            if ($this->isApiRequest(request())) {
                return $this->apiSuccess(null, 'Student deleted successfully');
            }

            // For web requests, redirect to the index page
            return redirect()->route('students.index')->with('success', 'Student deleted successfully');

        } catch (\Exception $e) {
            Log::error('Failed to delete student: ' . $e->getMessage(), [
                'student_id' => $student->id,
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollBack();
            
            // Check if this is an API request
            if ($this->isApiRequest(request())) {
                return $this->apiError('Failed to delete student', ['error' => $e->getMessage()], 500);
            }

            // For web requests, redirect with error message
            return redirect()->back()->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }

    /**
     * Get student statistics
     */
    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', Student::class);

        $stats = [
            'total_students' => Student::count(),
            'male_students' => Student::where('gender', 'Laki-laki')->count(),
            'female_students' => Student::where('gender', 'Perempuan')->count(),
            'students_with_scores' => Student::has('scores')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Student statistics retrieved successfully'
        ]);
    }

    /**
     * Get students for select dropdown
     */
    public function forSelect(): JsonResponse
    {
        $this->authorize('viewAny', Student::class);

        $students = Student::select('id', 'student_number', 'name')
            ->orderBy('student_number')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'text' => $student->student_number . ' - ' . $student->name,
                    'student_number' => $student->student_number,
                    'name' => $student->name,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $students,
            'message' => 'Students for select retrieved successfully'
        ]);
    }

    /**
     * Export students data
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $students = Student::with('scores.subject')
            ->orderBy('student_number')
            ->get();

        // Here you can implement CSV, Excel, or PDF export
        // For now, returning JSON data that can be processed by frontend
        
        return response()->json([
            'success' => true,
            'data' => $students,
            'message' => 'Students data exported successfully'
        ]);
    }

    /**
     * Check if student number already exists
     */
    public function checkStudentNumber(Request $request): JsonResponse
    {
        $studentNumber = $request->get('student_number');
        $excludeId = $request->get('exclude');

        $query = Student::where('student_number', $studentNumber);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Student number already exists' : 'Student number is available'
        ]);
    }

    /**
     * Debug method to test student update
     */
    public function debugUpdate(Request $request, Student $student)
    {
        try {
            $user = auth()->user();
            
            return response()->json([
                'success' => true,
                'debug' => [
                    'student_id' => $student->id,
                    'user_id' => $user ? $user->id : null,
                    'user_role' => $user ? $user->role : null,
                    'can_update' => $user ? $user->can('update', $student) : false,
                    'request_data' => $request->all(),
                    'student_data' => $student->toArray(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
