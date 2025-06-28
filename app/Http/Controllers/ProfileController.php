<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Score;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        $stats = [];

        // Get user stats based on role
        if ($user->role === 'dosen') {
            $stats = [
                'total_scores' => Score::where('teacher_id', $user->id)->count(),
                'validated_scores' => Score::where('teacher_id', $user->id)->where('validated', true)->count(),
                'pending_scores' => Score::where('teacher_id', $user->id)->where('validated', false)->count(),
                'average_score' => Score::where('teacher_id', $user->id)->avg('score'),
            ];
        } elseif ($user->role === 'admin' || $user->role === 'manajemen') {
            $stats = [
                'total_users' => User::count(),
                'total_scores' => Score::count(),
                'pending_validations' => Score::where('validated', false)->count(),
                'total_notifications' => Notification::where('user_id', $user->id)->count(),
            ];
        }

        // Get recent notifications
        $recentNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('profile.show', compact('stats', 'recentNotifications'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $user
                ]);
            }

            return redirect()->route('profile.show')
                ->with('success', 'Profile updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update profile')
                ->withInput();
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password updated successfully'
                ]);
            }

            return redirect()->route('profile.show')
                ->with('success', 'Password updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update password',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update password');
        }
    }

    /**
     * Get profile statistics
     */
    public function stats()
    {
        try {
            $user = Auth::user();
            $stats = [];

            if ($user->role === 'dosen') {
                // Teacher stats
                $stats['my_scores'] = Score::where('created_by', $user->id)->count();
                $stats['my_validated_scores'] = Score::where('created_by', $user->id)
                    ->where('validated', true)
                    ->count();
            } else {
                // Other roles stats
                $stats['my_notifications'] = Notification::where('user_id', $user->id)
                    ->where('read_at', null)
                    ->count();
                    
                if ($user->can('validate', Score::class)) {
                    $stats['my_pending_tasks'] = Score::where('validated', false)->count();
                } else {
                    $stats['my_pending_tasks'] = 0;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile statistics retrieved successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user account
     */
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user();

            // Admin cannot delete their own account if they're the only admin
            if ($user->role === 'admin') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete the last admin account'
                    ], 400);
                }
            }

            $request->validate([
                'password' => ['required', 'current_password'],
            ]);

            // Logout and delete account
            $user->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account deleted successfully'
                ]);
            }

            return redirect()->route('welcome')
                ->with('success', 'Your account has been deleted');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete account',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to delete account');
        }
    }
}
