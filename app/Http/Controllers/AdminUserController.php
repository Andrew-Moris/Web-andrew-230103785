<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    /**
     * Display the form for creating a new admin user.
     */
    public function create()
    {
        return view('admin.create-user');
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,editor,viewer',
        ], [
            'name.required' => 'The name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role.required' => 'The role is required.',
            'role.in' => 'The selected role is invalid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate the role using the UserPermission model
        if (!UserPermission::validateRole($request->role)) {
            return redirect()->back()
                ->with('error', 'The selected role is invalid.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Create the new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now()
            ]);

            // Get permissions from the UserPermission model
            $permissions = UserPermission::getRolePermissions($request->role);

            // Create user permissions
            UserPermission::create([
                'user_id' => $user->id,
                'role' => $request->role,
                'permissions' => $permissions
            ]);

            DB::commit();
            
            return redirect()->route('admin.create-user')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while creating the user: ' . $e->getMessage())
                ->withInput();
        }
    }



    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        dd($id);
        try {
            $user = User::with('userPermissions')->findOrFail($id);

            return view('admin.edit-user', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users-list')
                ->with('error', 'User not found.');
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,editor,viewer',
        ], [
            'name.required' => 'The name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role.required' => 'The role is required.',
            'role.in' => 'The selected role is invalid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate the role
        if (!UserPermission::validateRole($request->role)) {
            return redirect()->back()
                ->with('error', 'The selected role is invalid.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Update basic data
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            
            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            $user->update($updateData);

            // Get permissions from the UserPermission model
            $permissions = UserPermission::getRolePermissions($request->role);
            
            UserPermission::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'role' => $request->role,
                    'permissions' => $permissions
                ]
            );

            DB::commit();
            
            return redirect()->route('admin.users-list')
                ->with('success', 'User data updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while updating the user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Prevent deleting the current authenticated user
            if ($user->id === auth()->id()) {
                return redirect()->back()
                    ->with('error', 'You cannot delete your own account.');
            }

            // Ensure there is at least one other admin user remaining
            $adminCount = User::whereHas('userPermissions', function($query) {
                $query->where('role', 'admin');
            })->where('id', '!=', $id)->count();
            
            if ($adminCount === 0 && $user->userPermissions && $user->userPermissions->role === 'admin') {
                return redirect()->back()
                    ->with('error', 'Cannot delete the last admin user in the system.');
            }

            // Delete user permissions first
            UserPermission::where('user_id', $user->id)->delete();
            
            // Delete the user
            $user->delete();

            DB::commit();
            
            return redirect()->back()
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the user: ' . $e->getMessage());
        }
    }

    // Removed the duplicate getPermissionsByRole method as UserPermission::getRolePermissions is now used.
}
