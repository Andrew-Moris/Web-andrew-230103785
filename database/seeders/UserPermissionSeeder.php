<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Hash;

class UserPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Smith',
                'email' => 'john@test.com',
                'password' => Hash::make('123456'),
                'role' => 'editor',
                'permissions' => ['create', 'edit', 'reports']
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@test.com',
                'password' => Hash::make('123456'),
                'role' => 'viewer',
                'permissions' => ['reports']
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike@test.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'permissions' => ['create', 'edit', 'delete', 'reports', 'users']
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'permissions' => ['create', 'edit', 'delete', 'reports', 'users']
            ]
        ];

        foreach ($users as $userData) {
            // التحقق من وجود المستخدم مسبقاً
            $user = User::where('email', $userData['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                ]);
            }

            // التحقق من وجود صلاحيات المستخدم مسبقاً
            $existingPermission = UserPermission::where('user_id', $user->id)->first();
            
            if (!$existingPermission) {
                UserPermission::create([
                    'user_id' => $user->id,
                    'role' => $userData['role'],
                    'permissions' => $userData['permissions']
                ]);
            }
        }
    }
}
