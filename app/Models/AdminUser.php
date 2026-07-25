<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin_users';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'full_name',
        'role',
        'is_active',
        'last_login'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    // Override the default password column name
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Override the default username column
    public function getAuthIdentifierName()
    {
        return 'username';
    }

	 /**
     * Get the username (identifier) value
     */
    public function getAuthIdentifier()
    {
        return $this->username;
    }

    // Check if admin has specific role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Check if admin is super admin
    public function isSuperAdmin()
    {
        return $this->role === 'Super Admin';
    }

    // Check if admin is admin
    public function isAdmin()
    {
        return in_array($this->role, ['Admin', 'Super Admin']);
    }

    // Check if admin is moderator
    public function isModerator()
    {
        return $this->role === 'Moderator';
    }

	   /**
     * Mutator to hash password when setting
     */
    public function setPasswordHashAttribute($value)
    {
        // Only hash if it's not already hashed
        if (Hash::needsRehash($value)) {
            $this->attributes['password_hash'] = Hash::make($value);
        } else {
            $this->attributes['password_hash'] = $value;
        }
    }

	   /**
     * Get all permissions for this admin's role
     */
    public function permissions()
    {
        return DB::table('admin_permissions')
            ->join('admin_role_permissions', 'admin_permissions.id', '=', 'admin_role_permissions.permission_id')
            ->where('admin_role_permissions.role', $this->role)
            ->pluck('admin_permissions.slug')
            ->toArray();
    }

    /**
     * Check if admin has a specific permission
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions());
    }

    /**
     * Check if admin has any of the given permissions
     */
    public function hasAnyPermission(array $permissions)
    {
        return count(array_intersect($permissions, $this->permissions())) > 0;
    }

    /**
     * Check if admin has all of the given permissions
     */
    public function hasAllPermissions(array $permissions)
    {
        return count(array_intersect($permissions, $this->permissions())) === count($permissions);
    }
}
