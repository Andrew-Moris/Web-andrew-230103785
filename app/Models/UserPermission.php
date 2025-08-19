<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    protected static $roles = [
        'viewer' => ['reports'],
        'editor' => ['create', 'edit', 'reports'],
        'admin' => ['create', 'edit', 'delete', 'reports', 'users']
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Validate role
     */
    public static function validateRole($role)
    {
        return in_array($role, array_keys(self::$roles));
    }

    /**
     * Validate permissions
     */
    public static function validatePermissions($role, $permissions)
    {
        if (!self::validateRole($role)) {
            return false;
        }

        $allowedPermissions = self::$roles[$role];
        
        return !array_diff($permissions, $allowedPermissions);
    }

    /**
     * Get allowed permissions for a role
     */
    public static function getRolePermissions($role)
    {
        return self::validateRole($role) ? self::$roles[$role] : [];
    }

    /**
     * Get all roles
     */
    public static function getAllRoles()
    {
        return array_keys(self::$roles);
    }

    /**
     * Get role hierarchy level
     */
    public static function getRoleLevel($role)
    {
        $roles = array_flip(self::getAllRoles());
        return $roles[$role] ?? 0;
    }

    /**
     * Get permissions for current user's role
     */
    public function getCurrentRolePermissions()
    {
        return self::getRolePermissions($this->role);
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($permission)
    {
        if (!is_array($this->permissions)) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }
}
