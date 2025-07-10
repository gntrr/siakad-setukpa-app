<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Test Route for debugging
Route::get('/test-routing', function () {
    return view('test-routing');
})->name('test.routing');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Authentication Routes - uncomment if using Laravel Breeze/UI
// Auth::routes();

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management Routes (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users/role/{role}', [UserController::class, 'getByRole'])->name('users.by-role');
    });

    // Student Management Routes
    Route::middleware(['role:admin,manajemen'])->group(function () {
        Route::get('students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('students', [StudentController::class, 'store'])->name('students.store');
        Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::get('students/statistics', [StudentController::class, 'statistics'])->name('students.statistics');
    });

    // Student View Routes (All authenticated users)
    Route::middleware(['role:admin,manajemen,dosen'])->group(function () {
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('students/select/options', [StudentController::class, 'forSelect'])->name('students.select');
    });

    // Subject Management Routes
    Route::middleware(['role:admin,manajemen'])->group(function () {
        Route::resource('subjects', SubjectController::class)->except(['index', 'show']);
        Route::get('subjects/statistics', [SubjectController::class, 'statistics'])->name('subjects.statistics');
    });

    // Subject View Routes (All authenticated users including siswa)
    Route::middleware(['role:admin,manajemen,dosen,siswa'])->group(function () {
        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::get('subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
        Route::get('subjects/select/options', [SubjectController::class, 'forSelect'])->name('subjects.select');
    });

    // Score Management Routes (Dosen and Admin)
    Route::middleware(['role:dosen,admin'])->group(function () {
        Route::get('scores/create', [ScoreController::class, 'create'])->name('scores.create');
        Route::post('scores', [ScoreController::class, 'store'])->name('scores.store');
        Route::get('scores/{score}/edit', [ScoreController::class, 'edit'])->name('scores.edit');
        Route::put('scores/{score}', [ScoreController::class, 'update'])->name('scores.update');
        Route::delete('scores/{score}', [ScoreController::class, 'destroy'])->name('scores.destroy');
    });

    // Score View Routes (All authenticated users including siswa, but limited for siswa)
    Route::middleware(['role:admin,manajemen,dosen'])->group(function () {
        Route::get('scores/statistics', [ScoreController::class, 'statistics'])->name('scores.statistics');
        Route::get('scores/pending', [ScoreController::class, 'pending'])->name('scores.pending');
        Route::get('scores', [ScoreController::class, 'index'])->name('scores.index');
        Route::get('scores/{score}', [ScoreController::class, 'show'])->name('scores.show');
        Route::get('students/{student}/report', [ScoreController::class, 'studentReport'])->name('students.report');
    });

    // Routes khusus untuk siswa - hanya melihat mata pelajaran
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('schedule', function () {
            return view('schedule.index');
        })->name('schedule.index');
    });

    // Score Validation Routes (Admin & Management)
    Route::middleware(['role:admin,manajemen'])->group(function () {
        Route::post('scores/{score}/validate', [ScoreController::class, 'validateScore'])->name('scores.validate');
        Route::post('scores/bulk-validate', [ScoreController::class, 'bulkValidate'])->name('scores.bulk-validate');
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('{notification}', [NotificationController::class, 'show'])->name('show');
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('recent', [NotificationController::class, 'recent'])->name('recent');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Reports Routes (Admin & Management)
    Route::middleware(['role:admin,manajemen'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
        
        Route::get('students', function () {
            return view('reports.students');
        })->name('students');
        
        Route::get('scores', function () {
            return view('reports.scores');
        })->name('scores');
        
        Route::get('export/students', [StudentController::class, 'export'])->name('export.students');
        Route::get('export/scores', [ScoreController::class, 'export'])->name('export.scores');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Test route untuk debug student data
    Route::get('/debug-student/{id}', function($id) {
        $student = \App\Models\Student::findOrFail($id);
        dd([
            'id' => $student->id,
            'student_number' => $student->student_number,
            'name' => $student->name,
            'gender' => $student->gender,
            'birth_date' => $student->birth_date,
            'birth_date_formatted' => $student->birth_date ? $student->birth_date->format('Y-m-d') : null,
        ]);
    });

    // Test route untuk melihat form edit student
    Route::get('/test-student-edit/{id}', function($id) {
        $student = \App\Models\Student::findOrFail($id);
        return view('test-student-edit', compact('student'));
    });

    // Route untuk test tampilan data student di form edit
    Route::get('/check-student-edit/{id}', function($id) {
        try {
            $student = \App\Models\Student::findOrFail($id);
            
            // Simulasi controller edit method
            return view('students.edit', compact('student'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    })->middleware('auth');

    // Route test public untuk debug
    Route::get('/public-test-student/{id}', function($id) {
        $student = \App\Models\Student::findOrFail($id);
        return view('test-student-edit', compact('student'));
    });

    // Debug route untuk test update student
    Route::post('/debug-student-update/{student}', [\App\Http\Controllers\StudentController::class, 'debugUpdate'])->middleware('auth');

    // Route simple edit untuk testing
    Route::get('/students/{student}/edit-simple', function(\App\Models\Student $student) {
        return view('students.edit-simple', compact('student'));
    })->middleware('auth')->name('students.edit-simple');
});

// Load test routes
if (file_exists(__DIR__ . '/test.php')) {
    require __DIR__ . '/test.php';
}
