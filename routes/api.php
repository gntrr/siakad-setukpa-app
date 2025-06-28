<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:web')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication required routes
Route::middleware(['auth:web'])->group(function () {
      // Dashboard API Routes
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/score-statistics', [DashboardController::class, 'scoreStatistics']);
    Route::get('dashboard/recent-activity', [DashboardController::class, 'recentActivity']);
    
    // Profile API Routes
    Route::get('profile/stats', [ProfileController::class, 'stats']);
    
    // User Management Routes (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('users', UserController::class)->names([
            'index' => 'api.users.index',
            'store' => 'api.users.store',
            'show' => 'api.users.show',
            'update' => 'api.users.update',
            'destroy' => 'api.users.destroy',
        ]);
        Route::get('users/role/{role}', [UserController::class, 'getByRole'])->name('api.users.by-role');
    });

    // Student Management Routes (Admin & Management)
    Route::middleware(['role:admin,manajemen'])->group(function () {
        Route::apiResource('students', StudentController::class)->names([
            'index' => 'api.students.index',
            'store' => 'api.students.store',
            'show' => 'api.students.show',
            'update' => 'api.students.update',
            'destroy' => 'api.students.destroy',
        ]);
        Route::get('students/statistics', [StudentController::class, 'statistics']);
        Route::get('students/check-student-number', [StudentController::class, 'checkStudentNumber']);
    });

    // Student View Routes (Admin, Management & Dosen)
    Route::middleware(['role:admin,manajemen,dosen'])->group(function () {
        Route::get('students', [StudentController::class, 'index']);
        Route::get('students/{student}', [StudentController::class, 'show']);
        Route::get('students/select/options', [StudentController::class, 'forSelect']);
    });

    // Subject Management Routes (Admin & Management)
    Route::middleware(['role:admin,manajemen'])->group(function () {
        Route::apiResource('subjects', SubjectController::class)->names([
            'index' => 'api.subjects.index',
            'store' => 'api.subjects.store',
            'show' => 'api.subjects.show',
            'update' => 'api.subjects.update',
            'destroy' => 'api.subjects.destroy',
        ]);
        Route::get('subjects/statistics', [SubjectController::class, 'statistics']);
    });

    // Subject View Routes (Admin, Management & Dosen)
    Route::middleware(['role:admin,manajemen,dosen'])->group(function () {
        Route::get('subjects', [SubjectController::class, 'index']);
        Route::get('subjects/{subject}', [SubjectController::class, 'show']);
        Route::get('subjects/select/options', [SubjectController::class, 'forSelect']);
    });

    // Score Management Routes (Dosen can create/update their own scores)
    Route::middleware(['role:dosen'])->group(function () {
        Route::post('scores', [ScoreController::class, 'store']);
        Route::put('scores/{score}', [ScoreController::class, 'update']);
        Route::delete('scores/{score}', [ScoreController::class, 'destroy']);
    });    // Score View Routes (All authenticated users)
    Route::middleware(['role:admin,manajemen,dosen'])->group(function () {
        Route::get('scores', [ScoreController::class, 'index']);
        Route::get('scores/statistics', [ScoreController::class, 'statistics']);
        Route::get('scores/pending', [ScoreController::class, 'pending']);
        Route::get('scores/{score}', [ScoreController::class, 'show']);
        Route::get('students/{student}/report', [ScoreController::class, 'studentReport']);
    });
    
    // Score Validation Routes (Admin & Management)
    Route::middleware(['role:admin,manajemen'])->group(function () {
        Route::post('scores/{score}/validate', [ScoreController::class, 'validateScore']);
        Route::post('scores/bulk-validate', [ScoreController::class, 'bulkValidate']);
    });

    // Notification Routes (All authenticated users)
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('{notification}', [NotificationController::class, 'destroy']);
        Route::get('unread-count', [NotificationController::class, 'unreadCount']);
        Route::get('recent', [NotificationController::class, 'recent']);
    });

    // Dashboard & Statistics Routes
    Route::get('dashboard/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => \App\Models\User::count(),
                'total_students' => \App\Models\Student::count(),
                'total_subjects' => \App\Models\Subject::count(),
                'total_scores' => \App\Models\Score::count(),
                'validated_scores' => \App\Models\Score::where('validated', true)->count(),
                'pending_scores' => \App\Models\Score::where('validated', false)->count(),
            ],
            'message' => 'Dashboard statistics retrieved successfully'
        ]);
    })->middleware(['role:admin,manajemen']);
});
