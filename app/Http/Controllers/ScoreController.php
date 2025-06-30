<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Http\Requests\ScoreRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;

class ScoreController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if this is an API request
        if ($this->isApiRequest($request)) {
            $query = Score::with(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name']);

            // Filter by current teacher if user is dosen
            if (Auth::user()->isDosen()) {
                $query->where('teacher_id', Auth::id());
            }

            // Search functionality
            if ($request->has('search') && $request->search !== '') {
                $search = $request->get('search');
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('student_number', 'like', "%{$search}%");
                })->orWhereHas('subject', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }

            // Filter by student
            if ($request->has('student_id') && $request->student_id !== '') {
                $query->where('student_id', $request->student_id);
            }

            // Filter by subject
            if ($request->has('subject_id') && $request->subject_id !== '') {
                $query->where('subject_id', $request->subject_id);
            }

            // Filter by validation status
            if ($request->has('validated') && $request->validated !== '') {
                $query->where('validated', $request->validated === 'true');
            }

            // Filter by score range
            if ($request->has('min_score') && $request->min_score !== '') {
                $query->where('score', '>=', $request->min_score);
            }
            if ($request->has('max_score') && $request->max_score !== '') {
                $query->where('score', '<=', $request->max_score);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $scores = $query->paginate($perPage);

            return $this->apiSuccess($scores, 'Scores retrieved successfully');
        }

        // Return view for web requests
        $search = $request->get('search');
        $studentId = $request->get('student_id');
        $subjectId = $request->get('subject_id');
        $validated = $request->get('validated');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Get scores for web view
        $query = Score::with(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name']);

        // Filter by current teacher if user is dosen
        if (Auth::user()->isDosen()) {
            $query->where('teacher_id', Auth::id());
        }

        // Apply filters
        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        if ($validated !== null && $validated !== '') {
            $query->where('validated', $validated === 'true');
        }

        $query->orderBy($sortBy, $sortOrder);
        $scores = $query->paginate(15);

        // Get students and subjects for filter dropdowns
        $students = Student::select('id', 'name', 'student_number')->orderBy('name')->get();
        $subjects = Subject::select('id', 'name', 'code')->orderBy('name')->get();

        return view('scores.index', compact('scores', 'students', 'subjects', 'search', 'studentId', 'subjectId', 'validated', 'sortBy', 'sortOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $scoreData = $request->validated();
            $scoreData['teacher_id'] = Auth::id();
            $scoreData['validated'] = false; // New scores are not validated by default

            $score = Score::create($scoreData);
            $score->load(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name']);

            // Create notification for admin/management about new score
            $this->createScoreNotification($score, 'created');

            DB::commit();

            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'data' => $score,
                    'message' => 'Score created successfully'
                ], 201);
            }

            // Web request - redirect with success message
            return redirect()->route('scores.index')->with('success', 'Score created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create score',
                    'error' => $e->getMessage()
                ], 500);
            }

            // Web request - redirect with error message
            return redirect()->back()->withInput()->with('error', 'Failed to create score: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::select('id', 'name', 'student_number')->orderBy('name')->get();
        $subjects = Subject::select('id', 'name', 'code')->orderBy('name')->get();
        return view('scores.create', compact('students', 'subjects'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Score $score)
    {
        if (request()->expectsJson()) {
            $score->load(['student', 'subject', 'teacher:id,name']);

            return response()->json([
                'success' => true,
                'data' => $score,
                'message' => 'Score retrieved successfully'
            ]);
        }

        return view('scores.show', compact('score'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Score $score)
    {
        $students = Student::select('id', 'name', 'student_number')->orderBy('name')->get();
        $subjects = Subject::select('id', 'name', 'code')->orderBy('name')->get();
        return view('scores.edit', compact('score', 'students', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScoreRequest $request, Score $score)
    {
        $this->authorize('update', $score);

        try {
            DB::beginTransaction();

            $scoreData = $request->validated();
            $score->update($scoreData);

            $score->load(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name']);

            // Create notification about score update
            $this->createScoreNotification($score, 'updated');

            DB::commit();

            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'data' => $score,
                    'message' => 'Score updated successfully'
                ]);
            }

            // Web request - redirect with success message
            return redirect()->route('scores.index')->with('success', 'Score updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update score',
                    'error' => $e->getMessage()
                ], 500);
            }

            // Web request - redirect with error message
            return redirect()->back()->withInput()->with('error', 'Failed to update score: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Score $score)
    {
        try {
            DB::beginTransaction();

            $score->delete();

            DB::commit();

            // Check if this is an API request
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Score deleted successfully'
                ]);
            }

            // Web request - redirect with success message
            return redirect()->route('scores.index')->with('success', 'Score deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete score',
                    'error' => $e->getMessage()
                ], 500);
            }

            // Web request - redirect with error message
            return redirect()->back()->with('error', 'Failed to delete score: ' . $e->getMessage());
        }
    }

    /**
     * Validate a score (for admin/management)
     */
    public function validateScore(Request $request, Score $score)
    {
        $this->authorize('validate', $score);

        try {
            DB::beginTransaction();

            $score->update(['validated' => true]);
            $score->load(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name']);

            // Create notification about score validation
            $this->createScoreNotification($score, 'validated');

            DB::commit();

            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'data' => $score,
                    'message' => 'Score validated successfully'
                ]);
            }

            // Web request - redirect with success message
            return redirect()->back()->with('success', 'Score validated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to validate score',
                    'error' => $e->getMessage()
                ], 500);
            }

            // Web request - redirect with error message
            return redirect()->back()->with('error', 'Failed to validate score: ' . $e->getMessage());
        }
    }

    /**
     * Bulk validate scores
     */
    public function bulkValidate(Request $request)
    {
        $request->validate([
            'score_ids' => 'required|array',
            'score_ids.*' => 'exists:scores,id'
        ]);

        try {
            DB::beginTransaction();

            $scores = Score::whereIn('id', $request->score_ids)
                ->where('validated', false)
                ->get();

            foreach ($scores as $score) {
                $this->authorize('validate', $score);
            }

            Score::whereIn('id', $request->score_ids)->update(['validated' => true]);

            DB::commit();

            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => count($request->score_ids) . ' scores validated successfully'
                ]);
            }

            // Web request - redirect with success message
            return redirect()->back()->with('success', count($request->score_ids) . ' scores validated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to validate scores',
                    'error' => $e->getMessage()
                ], 500);
            }

            // Web request - redirect with error message
            return redirect()->back()->with('error', 'Failed to validate scores: ' . $e->getMessage());
        }
    }

    /**
     * Get score statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Score::class);

        $query = Score::query();

        // Filter by teacher if user is dosen
        if (Auth::user()->isDosen()) {
            $query->where('teacher_id', Auth::id());
        }

        $stats = [
            'total_scores' => $query->count(),
            'validated_scores' => $query->where('validated', true)->count(),
            'pending_scores' => $query->where('validated', false)->count(),
            'average_score' => round($query->avg('score'), 2),
            'highest_score' => $query->max('score'),
            'lowest_score' => $query->min('score'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Score statistics retrieved successfully'
        ]);
    }

    /**
     * Get student report card
     */
    public function studentReport(Student $student)
    {
        $this->authorize('view', $student);

        $scores = Score::with(['subject:id,code,name', 'teacher:id,name'])
            ->where('student_id', $student->id)
            ->get()
            ->groupBy('subject.name');

        $totalScore = $scores->flatten()->sum('score');
        $subjectCount = $scores->count();
        $averageScore = $subjectCount > 0 ? round($totalScore / $subjectCount, 2) : 0;

        // For API requests, return JSON response
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => [
                    'student' => $student,
                    'scores' => $scores,
                    'statistics' => [
                        'total_subjects' => $subjectCount,
                        'average_score' => $averageScore,
                        'total_score' => $totalScore,
                    ]
                ],
                'message' => 'Student report retrieved successfully'
            ]);
        }

        // For web requests, return a view
        return view('students.report', compact('student', 'scores', 'totalScore', 'subjectCount', 'averageScore'));
    }

    /**
     * Export scores data
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Score::class);

        $query = Score::with(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name']);

        // Filter by current teacher if user is dosen
        if (Auth::user()->isDosen()) {
            $query->where('teacher_id', Auth::id());
        }

        $scores = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $scores,
            'message' => 'Scores data exported successfully'
        ]);
    }

    /**
     * Get pending scores for validation
     */
    public function pending(Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            $this->authorize('validate', Score::class);

            $query = Score::with(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name'])
                ->where('validated', false);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $pendingScores = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $pendingScores,
                'message' => 'Pending scores retrieved successfully'
            ]);
        }

        // For web requests
        $this->authorize('validate', Score::class);
        
        $search = $request->get('search');
        $subjectId = $request->get('subject_id');
        $teacherId = $request->get('teacher_id');

        $query = Score::with(['student:id,student_number,name', 'subject:id,code,name', 'teacher:id,name'])
            ->where('validated', false);

        // Apply filters
        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $query->orderBy('created_at', 'desc');
        $pendingScores = $query->paginate(15);

        // Get filter options
        $subjects = Subject::select('id', 'name', 'code')->orderBy('name')->get();
        $teachers = User::where('role', 'dosen')->select('id', 'name')->orderBy('name')->get();

        return view('scores.pending', compact('pendingScores', 'subjects', 'teachers', 'search', 'subjectId', 'teacherId'));
    }

    /**
     * Create notification for score events
     */
    private function createScoreNotification(Score $score, string $action): void
    {
        $message = match($action) {
            'created' => "Nilai baru ditambahkan untuk {$score->student->name} pada mata pelajaran {$score->subject->name}",
            'updated' => "Nilai {$score->student->name} pada mata pelajaran {$score->subject->name} telah diperbarui",
            'validated' => "Nilai {$score->student->name} pada mata pelajaran {$score->subject->name} telah divalidasi",
            default => "Perubahan nilai untuk {$score->student->name}"
        };

        // Notify admin and management users
        $adminUsers = \App\Models\User::whereIn('role', ['admin', 'manajemen'])->get();
        
        foreach ($adminUsers as $user) {
            $user->notifications()->create([
                'message' => $message,
                'type' => 'nilai',
                'is_read' => false
            ]);
        }
    }

    /**
     * Get count of pending scores
     */
    public function getPendingCount()
    {
        $count = Score::where('validated', false)->count();
        
        return response()->json([
            'count' => $count
        ]);
    }
}
