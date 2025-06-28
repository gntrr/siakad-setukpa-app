<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Score;

class GlobalDataComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get recent notifications for dropdown
            $recentNotifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            // Get unread notification count
            $unreadNotificationCount = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
                
            // Get pending scores count for admins/managers
            $pendingScoresCount = 0;
            if ($user->can('validate', Score::class)) {
                $pendingScoresCount = Score::where('validated', false)->count();
            }
            
            $view->with([
                'globalNotifications' => $recentNotifications,
                'globalUnreadNotificationCount' => $unreadNotificationCount,
                'globalPendingScoresCount' => $pendingScoresCount
            ]);
        }
    }
}
