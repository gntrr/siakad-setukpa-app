<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('report_type', 'overview');
        $semester = $request->get('semester');
        $year = $request->get('year');
        $dateRange = $request->get('date_range');
        
        // Initialize data arrays
        $statistics = [];
        $students = collect();
        $subjects = collect();
        $performanceLabels = [];
        $performanceData = [];
        $gradeLabels = ['A', 'B', 'C', 'D', 'E'];
        $gradeData = [0, 0, 0, 0, 0];

        // Get basic statistics
        $statistics = $this->getStatistics($semester, $year, $dateRange);

        // Get students data if needed
        if ($reportType === 'students' || $reportType === 'overview') {
            $students = $this->getStudentsData($semester, $year, $dateRange);
        }

        // Get subjects data if needed
        if ($reportType === 'subjects' || $reportType === 'overview') {
            $subjects = $this->getSubjectsData($semester, $year, $dateRange);
        }

        // Get performance data for charts
        if ($reportType === 'performance' || $reportType === 'overview') {
            $performanceData = $this->getPerformanceData($semester, $year, $dateRange);
            $performanceLabels = $performanceData['labels'] ?? [];
            $performanceData = $performanceData['data'] ?? [];
            
            $gradeDistribution = $this->getGradeDistribution($semester, $year, $dateRange);
            $gradeData = $gradeDistribution;
        }

        return view('reports.index', compact(
            'statistics',
            'students',
            'subjects',
            'performanceLabels',
            'performanceData',
            'gradeLabels',
            'gradeData'
        ));
    }

    private function getStatistics($semester = null, $year = null, $dateRange = null)
    {
        $query = Student::query();
        
        // Apply filters if provided
        if ($semester || $year || $dateRange) {
            $query->whereHas('scores', function ($q) use ($semester, $year, $dateRange) {
                if ($year) {
                    $q->whereYear('created_at', $year);
                }
                if ($dateRange) {
                    $dates = explode(' to ', $dateRange);
                    if (count($dates) === 2) {
                        $q->whereBetween('created_at', [$dates[0], $dates[1]]);
                    }
                }
            });
        }

        $totalStudents = $query->count();
        $activeStudents = $query->where('status', 'aktif')->count();
        
        // Calculate average GPA
        $averageScore = Score::query()
            ->when($year, function ($q, $year) {
                $q->whereYear('created_at', $year);
            })
            ->when($dateRange, function ($q, $dateRange) {
                $dates = explode(' to ', $dateRange);
                if (count($dates) === 2) {
                    $q->whereBetween('created_at', [$dates[0], $dates[1]]);
                }
            })
            ->avg('score') ?? 0;

        // Students with low performance (average score < 60)
        $lowPerformingStudents = Student::query()
            ->withAvg('scores', 'score')
            ->havingRaw('scores_avg_score < 60')
            ->count();

        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'average_gpa' => $averageScore / 25, // Convert to 4.0 scale approximation
            'low_performing_students' => $lowPerformingStudents
        ];
    }

    private function getStudentsData($semester = null, $year = null, $dateRange = null)
    {
        $query = Student::query()
            ->withCount('scores')
            ->withAvg('scores', 'score');

        // Apply filters if provided
        if ($semester || $year || $dateRange) {
            $query->whereHas('scores', function ($q) use ($semester, $year, $dateRange) {
                if ($year) {
                    $q->whereYear('created_at', $year);
                }
                if ($dateRange) {
                    $dates = explode(' to ', $dateRange);
                    if (count($dates) === 2) {
                        $q->whereBetween('created_at', [$dates[0], $dates[1]]);
                    }
                }
            });
        }

        return $query->orderBy('scores_avg_score', 'desc')->get();
    }

    private function getSubjectsData($semester = null, $year = null, $dateRange = null)
    {
        $query = Subject::query()
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->withCount(['scores as passed_scores_count' => function ($q) {
                $q->where('score', '>=', 60);
            }]);

        // Apply filters if provided
        if ($semester || $year || $dateRange) {
            $query->whereHas('scores', function ($q) use ($semester, $year, $dateRange) {
                if ($year) {
                    $q->whereYear('created_at', $year);
                }
                if ($dateRange) {
                    $dates = explode(' to ', $dateRange);
                    if (count($dates) === 2) {
                        $q->whereBetween('created_at', [$dates[0], $dates[1]]);
                    }
                }
            });
        }

        // Add default values for fields that don't exist in the database yet
        return $query->get()->map(function ($subject) {
            // Set default values for missing columns
            $subject->credits = 3; // Default 3 SKS
            $subject->semester = 1; // Default semester 1
            return $subject;
        });
    }

    private function getPerformanceData($semester = null, $year = null, $dateRange = null)
    {
        $query = Score::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, AVG(score) as avg_score')
            ->groupBy('month')
            ->orderBy('month');

        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        if ($dateRange) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }

        $results = $query->get();
        
        $labels = [];
        $data = [];
        
        foreach ($results as $result) {
            $labels[] = $result->month;
            $data[] = round($result->avg_score, 2);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getGradeDistribution($semester = null, $year = null, $dateRange = null)
    {
        $query = Score::query();

        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        if ($dateRange) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }

        $scores = $query->get();
        
        $gradeCount = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0
        ];

        foreach ($scores as $score) {
            $grade = $score->grade;
            if (in_array($grade, ['A', 'A-'])) {
                $gradeCount['A']++;
            } elseif (in_array($grade, ['B+', 'B', 'B-'])) {
                $gradeCount['B']++;
            } elseif (in_array($grade, ['C+', 'C', 'C-'])) {
                $gradeCount['C']++;
            } elseif (in_array($grade, ['D+', 'D'])) {
                $gradeCount['D']++;
            } else {
                $gradeCount['E']++;
            }
        }

        return array_values($gradeCount);
    }

    public function export(Request $request)
    {
        // This method can be implemented for exporting reports
        // For now, it's a placeholder
        return response()->json(['message' => 'Export functionality coming soon']);
    }
}
