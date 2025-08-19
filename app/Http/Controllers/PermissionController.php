<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display permissions page (Admin only)
     */
    public function index()
    {
        try {
            $currentUser = Auth::user();
    
            // If the current user has no permissions, create default ones.
            if (!$currentUser->userPermissions) {
                UserPermission::create([
                    'user_id' => $currentUser->id,
                    'role' => 'viewer',
                    'permissions' => ['reports'],
                ]);
                // Reload user with permissions
                $currentUser = User::with('userPermissions')->find($currentUser->id);
            }
            
            // Check if user has admin permissions (users permission)
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                // Redirect non-admin users with clear message
                return redirect()->route('dashboard')->with('error', 'عذراً، هذه الصفحة مخصصة للأدمن فقط. لا تملك صلاحيات الوصول لإدارة المستخدمين.');
            }
            
            // Admin user - show all users
            $users = User::with('userPermissions')->orderBy('created_at', 'desc')->get();

            // Define roles and their permissions
            $roles = [
                'admin' => ['create', 'edit', 'delete', 'reports', 'users'],
                'editor' => ['create', 'edit', 'reports'],
                'viewer' => ['reports']
            ];
            
            return view('permissions', compact('users', 'currentUser', 'roles'));
        } catch (\Exception $e) {
            Log::error('Error loading permissions page: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'حدث خطأ في تحميل صفحة الصلاحيات.');
        }
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request)
    {
        try {
            // Check if user has admin permissions
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'role' => 'required|in:admin,editor,viewer',
                'permissions' => 'array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }

            // Define role permissions
            $rolePermissions = [
                'admin' => ['create', 'edit', 'delete', 'reports', 'users'],
                'editor' => ['create', 'edit', 'reports'],
                'viewer' => ['reports']
            ];

            // Filter permissions based on role
            $permissions = array_intersect($request->permissions ?? [], $rolePermissions[$request->role]);

            // Ensure role permissions are included
            $permissions = array_unique(array_merge($permissions, $rolePermissions[$request->role]));

            // Update or create user permissions
            $userPermission = UserPermission::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'role' => $request->role,
                    'permissions' => $permissions
                ]
            );

            $user = User::find($request->user_id);

            // Clear cache
            Cache::forget('user_permissions_' . $request->user_id);

            Log::info('User permissions updated', [
                'admin_user' => $currentUser->id,
                'target_user' => $user->id,
                'new_role' => $request->role,
                'permissions' => $permissions
            ]);

            return response()->json([
                'success' => true,
                'message' => "Permissions updated successfully for {$user->name}!",
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $request->role,
                    'permissions' => $permissions
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating permissions.'
            ], 500);
        }
    }

    /**
     * Get user permissions
     */
    public function getUserPermissions($userId)
    {
        try {
            $currentUser = Auth::user();
            
            // Allow users to view their own permissions, or require admin permissions for others
            if ($currentUser->id != $userId && (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. You can only view your own permissions or need admin permissions to view others.'
                ], 403);
            }

            $user = User::with('userPermissions')->findOrFail($userId);
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'permissions' => $user->userPermissions ? [
                    'role' => $user->userPermissions->role,
                    'permissions' => $user->userPermissions->permissions
                ] : null,
                'last_updated' => $user->userPermissions ? $user->userPermissions->updated_at : null
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting user permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'User not found or error occurred.'
            ], 404);
        }
    }

    /**
     * Create a new role
     */
    public function createRole(Request $request)
    {
        try {
            // Check if user has admin permissions
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'role_name' => 'required|string|max:255|unique:user_permissions,role',
                'permissions' => 'array',
                'permissions.*' => 'string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }

            // Create a dummy user permission entry for the new role
            // This is a workaround as roles are not stored in a separate table
            // In a real application, roles would have their own table
            UserPermission::create([
                'user_id' => 0, // Dummy user_id, indicates a role definition
                'role' => $request->role_name,
                'permissions' => $request->permissions ?? []
            ]);

            Log::info('New role created', [
                'admin_user' => $currentUser->id,
                'role_name' => $request->role_name,
                'permissions' => $request->permissions ?? []
            ]);

            return response()->json([
                'success' => true,
                'message' => "Role '{$request->role_name}' created successfully!"
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the role.'
            ], 500);
        }
    }

    /**
     * Update an existing role
     */
    public function updateRole(Request $request)
    {
        try {
            // Check if user has admin permissions
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'original_role_name' => 'required|string|max:255',
                'new_role_name' => 'required|string|max:255',
                'permissions' => 'array',
                'permissions.*' => 'string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }

            // Find the role to update (using the dummy user_id = 0)
            $roleToUpdate = UserPermission::where('role', $request->original_role_name)
                                        ->where('user_id', 0) // Only update role definitions
                                        ->first();

            if (!$roleToUpdate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.'
                ], 404);
            }

            // Update the role name and permissions
            $roleToUpdate->update([
                'role' => $request->new_role_name,
                'permissions' => $request->permissions ?? []
            ]);

            Log::info('Role updated', [
                'admin_user' => $currentUser->id,
                'original_role_name' => $request->original_role_name,
                'new_role_name' => $request->new_role_name,
                'permissions' => $request->permissions ?? []
            ]);

            return response()->json([
                'success' => true,
                'message' => "Role '{$request->original_role_name}' updated to '{$request->new_role_name}' successfully!"
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the role.'
            ], 500);
        }
    }

    /**
     * Delete a role
     */
    public function deleteRole(Request $request)
    {
        try {
            // Check if user has admin permissions
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'role_name' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }

            // Prevent deletion of default roles
            if (in_array($request->role_name, ['admin', 'editor', 'viewer'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete default roles (admin, editor, viewer).'
                ], 403);
            }

            // Find and delete the role definition
            $roleToDelete = UserPermission::where('role', $request->role_name)
                                        ->where('user_id', 0) // Only delete role definitions
                                        ->first();

            if (!$roleToDelete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.'
                ], 404);
            }

            $roleToDelete->delete();

            // Optionally, reassign users with this role to a default role (e.g., 'viewer')
            UserPermission::where('role', $request->role_name)
                            ->where('user_id', '!=', 0) // Exclude role definitions
                            ->update(['role' => 'viewer']);

            Log::info('Role deleted', [
                'admin_user' => $currentUser->id,
                'role_name' => $request->role_name
            ]);

            return response()->json([
                'success' => true,
                'message' => "Role '{$request->role_name}' deleted successfully!"
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the role.'
            ], 500);
        }
    }

    /**
     * Update a user's role
     */
    public function updateUserRole(Request $request)
    {
        try {
            // Check if user has admin permissions
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'role' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }

            // Find the user's permissions entry
            $userPermission = UserPermission::where('user_id', $request->user_id)->first();

            if (!$userPermission) {
                // If no entry exists, create one
                $userPermission = UserPermission::create([
                    'user_id' => $request->user_id,
                    'role' => $request->role,
                    'permissions' => [] // Default empty permissions for new entry
                ]);
            } else {
                // Define roles and their default permissions
                $rolePermissions = [
                    'admin' => ['create', 'edit', 'delete', 'reports', 'users'],
                    'editor' => ['create', 'edit', 'reports'],
                    'viewer' => ['reports']
                ];

                // Get the default permissions for the new role
                $defaultPermissions = $rolePermissions[$request->role] ?? [];

                // Update the role and permissions
                $userPermission->update([
                    'role' => $request->role,
                    'permissions' => $defaultPermissions
                ]);
            }

            // Clear cache for the updated user
            Cache::forget('user_permissions_' . $request->user_id);

            // If the updated user is the currently authenticated user, refresh their session
            if (Auth::id() == $request->user_id) {
                Auth::logout();
                Auth::login(User::with('userPermissions')->find($request->user_id));
            }

            Log::info('User role updated', [
                'admin_user' => $currentUser->id,
                'target_user_id' => $request->user_id,
                'new_role' => $request->role
            ]);

            return response()->json([
                'success' => true,
                'message' => "User role updated to '{$request->role}' successfully!"
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating user role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user role.'
            ], 500);
        }
    }

    /**
     * Delete user permissions
     */
    public function deletePermissions($userId)
    {
        try {
            // Check if user has admin permissions
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            $userPermission = UserPermission::where('user_id', $userId)->first();

            if (!$userPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'User permissions not found.'
                ], 404);
            }

            $userPermission->delete();

            // Clear cache
            Cache::forget('user_permissions_' . $userId);

            Log::info('User permissions deleted', [
                'admin_user' => $currentUser->id,
                'target_user' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User permissions deleted successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting user permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting user permissions.'
            ], 500);
        }
    }

    /**
     * Get all users (admin only)
     */
    public function getAllUsers()
    {
        try {
            // Check if user has admin permissions
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            $users = User::with('userPermissions')->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'users' => $users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at,
                        'user_permissions' => $user->userPermissions ? [
                            'role' => $user->userPermissions->role,
                            'permissions' => $user->userPermissions->permissions
                        ] : null
                    ];
                }),
                'count' => $users->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting all users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching users.'
            ], 500);
        }
    }

    /**
     * Reset user permissions
     */
    public function resetPermissions(Request $request)
    {
        try {
            $currentUser = Auth::user();
            if (!$currentUser->userPermissions || !in_array('users', $currentUser->userPermissions->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin permissions required.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user ID.'
                ], 422);
            }

            $user = User::find($request->user_id);
            
            // Delete user permissions
            UserPermission::where('user_id', $request->user_id)->delete();
            
            // Clear cache
            Cache::forget('user_permissions_' . $request->user_id);

            Log::info('User permissions reset', [
                'admin_user' => $currentUser->id,
                'target_user' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => "Permissions reset successfully for {$user->name}!"
            ]);

        } catch (\Exception $e) {
            Log::error('Error resetting permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resetting permissions.'
            ], 500);
        }
    }

}