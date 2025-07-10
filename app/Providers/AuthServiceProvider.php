<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Score;
use App\Policies\UserPolicy;
use App\Policies\StudentPolicy;
use App\Policies\SubjectPolicy;
use App\Policies\ScorePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Student::class => StudentPolicy::class,
        Subject::class => SubjectPolicy::class,
        Score::class => ScorePolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Additional gates can be defined here
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-students', function (User $user) {
            return $user->isAdmin() || $user->isManajemen();
        });

        Gate::define('manage-subjects', function (User $user) {
            return $user->isAdmin() || $user->isManajemen();
        });

        Gate::define('input-scores', function (User $user) {
            return $user->isDosen();
        });

        Gate::define('validate-scores', function (User $user) {
            return $user->isAdmin() || $user->isManajemen();
        });

        // Gates untuk siswa - hanya bisa melihat mata pelajaran dan jadwal
        Gate::define('view-subjects', function (User $user) {
            return $user->isAdmin() || $user->isManajemen() || $user->isDosen() || $user->isSiswa();
        });

        Gate::define('view-schedule', function (User $user) {
            return $user->isAdmin() || $user->isManajemen() || $user->isDosen() || $user->isSiswa();
        });

        Gate::define('view-own-scores', function (User $user) {
            return $user->isSiswa();
        });
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
