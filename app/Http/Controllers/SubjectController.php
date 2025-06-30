<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Requests\SubjectRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiResponseTrait;

class SubjectController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'code');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Include scores count for performance optimization
        $query->withCount('scores');

        // Pagination
        $perPage = $request->get('per_page', 15);
        $subjects = $query->paginate($perPage);

        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $subjects,
                'message' => 'Subjects retrieved successfully'
            ]);
        }

        // For web requests, get additional data
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'code');
        $sortOrder = $request->get('sort_order', 'asc');

        // Return view for web requests with data
        return view('subjects.index', compact('subjects', 'search', 'sortBy', 'sortOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        try {
            DB::beginTransaction();

            $subject = Subject::create($request->validated());

            DB::commit();

            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiSuccess($subject, 'Subject created successfully', 201);
            }

            // For web requests, redirect to the index page
            return redirect()->route('subjects.index')->with('success', 'Subject created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiError('Failed to create subject', ['error' => $e->getMessage()], 500);
            }

            // Web request error handling
            return redirect()->back()->withInput()->with('error', 'Failed to create subject: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        if ($this->isApiRequest()) {
            // Load related scores with student and teacher information
            $subject->load(['scores.student:id,student_number,name', 'scores.teacher:id,name']);

            return $this->apiSuccess($subject, 'Subject retrieved successfully');
        }

        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, Subject $subject)
    {
        try {
            $this->authorize('update', $subject);
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return $this->apiError('Tidak memiliki izin untuk mengupdate mata pelajaran', ['error' => $e->getMessage()], 403);
            }
            
            return redirect()->back()->with('error', 'Tidak memiliki izin untuk mengupdate mata pelajaran');
        }

        try {
            DB::beginTransaction();

            $subject->update($request->validated());

            DB::commit();

            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiSuccess($subject->fresh(), 'Subject updated successfully');
            }

            // For web requests, redirect to the index page
            return redirect()->route('subjects.index')->with('success', 'Subject updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($this->isApiRequest($request)) {
                return $this->apiError('Failed to update subject', ['error' => $e->getMessage()], 500);
            }

            // Web request error handling
            return redirect()->back()->withInput()->with('error', 'Failed to update subject: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        try {
            DB::beginTransaction();

            // Check if subject has scores
            if ($subject->scores()->exists()) {
                DB::rollBack();
                
                // Check if this is an API request
                if ($this->isApiRequest(request())) {
                    return $this->apiError('Cannot delete subject with existing scores. Please delete scores first.', [], 422);
                }

                // Web request error handling
                return redirect()->back()->with('error', 'Cannot delete subject with existing scores. Please delete scores first.');
            }

            $subject->delete();

            DB::commit();

            // Check if this is an API request
            if ($this->isApiRequest(request())) {
                return $this->apiSuccess(null, 'Subject deleted successfully');
            }

            // For web requests, redirect to the index page
            return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($this->isApiRequest(request())) {
                return $this->apiError('Failed to delete subject', ['error' => $e->getMessage()], 500);
            }

            // Web request error handling
            return redirect()->back()->with('error', 'Failed to delete subject: ' . $e->getMessage());
        }
    }

    /**
     * Get subjects for select dropdown
     */
    public function forSelect(): JsonResponse
    {
        $this->authorize('viewAny', Subject::class);

        $subjects = Subject::select('id', 'code', 'name')
            ->orderBy('code')
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'text' => $subject->code . ' - ' . $subject->name,
                    'code' => $subject->code,
                    'name' => $subject->name,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $subjects,
            'message' => 'Subjects for select retrieved successfully'
        ]);
    }

    /**
     * Get subject statistics
     */
    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', Subject::class);

        $stats = [
            'total_subjects' => Subject::count(),
            'subjects_with_scores' => Subject::has('scores')->count(),
            'average_scores_per_subject' => round(Subject::withCount('scores')->avg('scores_count'), 2),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Subject statistics retrieved successfully'
        ]);
    }
}
