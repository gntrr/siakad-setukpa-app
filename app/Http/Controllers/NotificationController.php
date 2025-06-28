<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id());

        // Filter by read status
        if ($request->has('is_read') && $request->is_read !== '') {
            $query->where('is_read', $request->is_read === 'true');
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Sorting
        $query->orderBy('created_at', 'desc');

        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            // Pagination
            $perPage = $request->get('per_page', 15);
            $notifications = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'message' => 'Notifications retrieved successfully'
            ]);
        }

        // Return view for web requests
        $notifications = $query->paginate(15);
        $unreadCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();
        $readCount = Notification::where('user_id', Auth::id())->where('is_read', true)->count();
        $todayCount = Notification::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();
        return view('notifications.index', compact('notifications', 'unreadCount', 'readCount', 'todayCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to notification'
            ], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all user notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            DB::beginTransaction();

            Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to notification'
            ], 403);
        }

        try {
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(): JsonResponse
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => ['unread_count' => $count],
            'message' => 'Unread count retrieved successfully'
        ]);
    }

    /**
     * Get recent notifications (for real-time updates)
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'message' => 'Recent notifications retrieved successfully'
        ]);
    }
}
