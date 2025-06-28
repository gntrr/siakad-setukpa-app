<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Score;
use Illuminate\Support\Facades\Auth;

class LayoutComposer
{
    public function compose(View $view)
    {
        $pendingScoresCount = 0;
        
        if (Auth::check() && Auth::user()->can('validate', Score::class)) {
            $pendingScoresCount = Score::where('status', 'pending')->count();
        }
        
        $view->with('pendingScoresCount', $pendingScoresCount);
    }
}
