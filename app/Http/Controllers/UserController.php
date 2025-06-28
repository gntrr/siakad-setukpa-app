<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            $query = User::query();

            // Search functionality
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by role
            if ($request->has('role') && $request->role !== '') {
                $query->where('role', $request->role);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ]);
        }

        // Return view for web requests
        $search = $request->get('search');
        $role = $request->get('role');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Get users for web view
        $query = User::query();

        // Apply filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        // Sorting
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $users = $query->paginate(15);

        return view('users.index', compact('users', 'search', 'role', 'sortBy', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $userData = $request->validated();
            $userData['password'] = Hash::make($userData['password']);

            $user = User::create($userData);

            DB::commit();

            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'data' => $user,
                    'message' => 'User created successfully'
                ], 201);
            }

            // For web requests, redirect with success message
            return redirect()->route('users.show', $user)
                ->with('success', 'Pengguna berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user',
                    'error' => $e->getMessage()
                ], 500);
            }

            // For web requests, redirect back with error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User retrieved successfully'
            ]);
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        try {
            DB::beginTransaction();

            $userData = $request->validated();
            
            // Only hash password if it's provided
            if (!empty($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            } else {
                unset($userData['password']);
            }

            $user->update($userData);

            DB::commit();

            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'data' => $user->fresh(),
                    'message' => 'User updated successfully'
                ]);
            }

            // For web requests, redirect with success message
            return redirect()->route('users.show', $user)
                ->with('success', 'Pengguna berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user',
                    'error' => $e->getMessage()
                ], 500);
            }

            // For web requests, redirect back with error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        try {
            DB::beginTransaction();

            // Check if user has related data
            if ($user->scores()->exists()) {
                // Check if this is an API request
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete user with existing score records'
                    ], 422);
                }

                // For web requests, redirect back with error
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pengguna yang memiliki data nilai.');
            }

            $userName = $user->name;
            $user->delete();

            DB::commit();

            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            }

            // For web requests, redirect to index with success message
            return redirect()->route('users.index')
                ->with('success', "Pengguna {$userName} berhasil dihapus!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user',
                    'error' => $e->getMessage()
                ], 500);
            }

            // For web requests, redirect back with error
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Get users by role
     */
    public function getByRole(Request $request, string $role): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = User::where('role', $role)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => "Users with role {$role} retrieved successfully"
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = auth()->user();
        $this->authorize('update', $user);

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 
                'email', 
                'max:100',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            DB::beginTransaction();

            $userData = $request->only(['name', 'email']);
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $user->fresh(),
                'message' => 'Profile updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
