<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\GlobalDataComposer;
use App\Models\Score;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share global data with layout
        View::composer('layouts.app', GlobalDataComposer::class);

        // Share pending scores count with the layout
        View::composer('layouts.app', function ($view) {
            $pendingScoresCount = 0;
            
            if (Auth::check() && Auth::user()->can('validate', Score::class)) {
                $pendingScoresCount = Score::where('validated', false)->count();
            }
            
            $view->with('pendingScoresCount', $pendingScoresCount);
        });
    }
}
