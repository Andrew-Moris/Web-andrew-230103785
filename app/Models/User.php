<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone', // إضافة حقل الهاتف
        'credit', // إضافة حقل الرصيد
        'google_id',
        'google_token',
        'google_refresh_token',
        'email_verified_at',
    ];

    /**
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
    ];

    /**
     *
     * @return array<string, 
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * إزالة setPasswordAttribute لتجنب التشفير المزدوج
     */
    // public function setPasswordAttribute($value)
    // {
    //     if ($value) {
    //         $this->attributes['password'] = bcrypt($value);
    //     }
    // }

    public function userPermissions()
    {
        return $this->hasOne(UserPermission::class);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->userPermissions) {
            return false;
        }

        $permissions = $this->userPermissions->permissions;
        if (is_array($permissions)) {
            return in_array($permission, $permissions);
        }

        return false;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        if (!$this->userPermissions) {
            return false;
        }

        return $this->userPermissions->role === $role;
    }

    /**
     * Get all permissions for this user
     */
    public function getPermissions()
    {
        if (!$this->userPermissions) {
            return [];
        }

        return $this->userPermissions->permissions;
    }

    /**
     * Get user's role
     */
    public function getRole()
    {
        if (!$this->userPermissions) {
            return null;
        }

        return $this->userPermissions->role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is editor
     */
    public function isEditor()
    {
        return $this->hasRole('editor');
    }

    /**
     * Check if user is viewer
     */
    public function isViewer()
    {
        return $this->hasRole('viewer');
    }

    /**
     * Get user's role hierarchy level
     */
    public function getRoleLevel()
    {
        $roles = ['viewer' => 1, 'editor' => 2, 'admin' => 3];
        $role = $this->getRole();
        
        return $role ? $roles[$role] : 0;
    }

    /**
     * Check if user has higher or equal role level
     */
    public function hasHigherRoleLevel($targetRole)
    {
        $roles = ['viewer' => 1, 'editor' => 2, 'admin' => 3];
        $userLevel = $this->getRoleLevel();
        $targetLevel = $roles[$targetRole] ?? 0;
        
        return $userLevel >= $targetLevel;
    }

    /**
     * Get user's purchases
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer()
    {
        return $this->hasRole('customer');
    }

    /**
     * Check if user is an employee
     */
    public function isEmployee()
    {
        return $this->hasRole('employee') || $this->hasRole('admin');
    }

    /**
     * Add credit to user account
     */
    public function addCredit($amount)
    {
        $this->increment('credit', $amount);
    }

    /**
     * Deduct credit from user account
     */
    public function deductCredit($amount)
    {
        if ($this->credit >= $amount) {
            $this->decrement('credit', $amount);
            return true;
        }
        return false;
    }

    /**
     * Check if user has enough credit
     */
    public function hasEnoughCredit($amount)
    {
        return $this->credit >= $amount;
    }
}
