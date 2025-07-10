<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Score;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{    /**
     * Show dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $stats = [];
        $recentActivity = [];
        $scoreStatistics = [];

        // Get stats
        if ($user->can('viewAny', Student::class)) {
            $stats['total_students'] = Student::count();
        }

        if ($user->can('viewAny', Subject::class)) {
            $stats['total_subjects'] = Subject::count();
        }

        if ($user->can('viewAny', Score::class)) {
            if ($user->role === 'dosen') {
                $stats['total_scores'] = Score::where('teacher_id', $user->id)->count();
                $stats['validated_scores'] = Score::where('teacher_id', $user->id)->where('validated', true)->count();
                $stats['pending_scores'] = Score::where('teacher_id', $user->id)->where('validated', false)->count();
            } else {
                $stats['total_scores'] = Score::count();
                $stats['validated_scores'] = Score::where('validated', true)->count();
                $stats['pending_scores'] = Score::where('validated', false)->count();
            }
        }

        if ($user->can('viewAny', User::class)) {
            $stats['total_users'] = User::count();
        }

        // Get recent notifications
        $recentNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();        // Get score statistics for chart
        $scoreStatistics = [];
        if ($user->can('viewAny', Score::class)) {
            $scoreQuery = Score::query();
            if ($user->role === 'dosen') {
                $scoreQuery->where('teacher_id', $user->id);
            }
            
            // Get all scores and calculate grade distribution
            $scores = $scoreQuery->get();
            
            foreach ($scores as $score) {
                $grade = $score->grade; // Uses the accessor we added
                if (!isset($scoreStatistics[$grade])) {
                    $scoreStatistics[$grade] = 0;
                }
                $scoreStatistics[$grade]++;
            }
        }

        // Data khusus untuk siswa
        $studentData = [];
        if ($user->isSiswa()) {
            // Untuk siswa, tampilkan mata pelajaran yang tersedia
            $studentData['available_subjects'] = Subject::count();
            // Bisa ditambahkan jadwal atau informasi lain khusus siswa
        }

        return view('dashboard', compact('stats', 'recentNotifications', 'scoreStatistics', 'studentData'));
    }

    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        try {
            $user = Auth::user();
            $stats = [];

            // Total students
            if ($user->can('viewAny', Student::class)) {
                $stats['total_students'] = Student::count();
            }

            // Total subjects
            if ($user->can('viewAny', Subject::class)) {
                $stats['total_subjects'] = Subject::count();
            }

            // Total scores
            if ($user->can('viewAny', Score::class)) {
                if ($user->role === 'teacher') {
                    // Teacher can only see their own scores
                    $stats['total_scores'] = Score::where('created_by', $user->id)->count();
                } else {
                    $stats['total_scores'] = Score::count();
                }
            }

            // Pending scores (for admin and management)
            if ($user->can('validate', Score::class)) {
                $stats['pending_scores'] = Score::where('validated', false)->count();
            }

            // Total users (admin only)
            if ($user->can('viewAny', User::class)) {
                $stats['total_users'] = User::count();
            }

            return response()->json([
                'success' => true,
                'message' => 'Dashboard statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get score statistics for chart
     */
    public function scoreStatistics()
    {
        try {
            $user = Auth::user();
            
            if (!$user->can('viewAny', Score::class)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Get score statistics by month
            $query = Score::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('AVG(score) as average_score'),
                    DB::raw('COUNT(*) as total_scores')
                )
                ->where('validated', true)
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6);

            // Filter by teacher if user is teacher
            if ($user->role === 'teacher') {
                $query->where('created_by', $user->id);
            }

            $statistics = $query->get();

            // Format data for chart
            $labels = [];
            $values = [];

            foreach ($statistics->reverse() as $stat) {
                $labels[] = date('M Y', strtotime($stat->month . '-01'));
                $values[] = round($stat->average_score, 2);
            }

            return response()->json([
                'success' => true,
                'message' => 'Score statistics retrieved successfully',
                'data' => [
                    'labels' => $labels,
                    'values' => $values
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load score statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent activity
     */
    public function recentActivity(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 10);

            $query = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit($limit);

            $notifications = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Recent activity retrieved successfully',
                'data' => $notifications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load recent activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
