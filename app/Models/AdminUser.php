<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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

    /**
     * Override the default password column name for Auth
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Check if admin has specific role (case-insensitive)
     */
    public function hasRole($role)
    {
        return strcasecmp((string)$this->role, (string)$role) === 0;
    }

    /**
     * Check if admin is super admin (case-insensitive)
     */
    public function isSuperAdmin()
    {
        return strcasecmp((string)$this->role, 'Super Admin') === 0;
    }

    /**
     * Check if admin is admin (case-insensitive)
     */
    public function isAdmin()
    {
        return in_array(strtolower((string)$this->role), ['admin', 'super admin']);
    }

    /**
     * Check if admin is moderator (case-insensitive)
     */
    public function isModerator()
    {
        return strcasecmp((string)$this->role, 'Moderator') === 0;
    }

    /**
     * Mutator to hash password when setting
     */
    public function setPasswordHashAttribute($value)
    {
        if (empty($value)) return;

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
        try {
            if (!Schema::hasTable('admin_permissions') || !Schema::hasTable('admin_role_permissions')) {
                return [];
            }

            return DB::table('admin_permissions')
                ->join('admin_role_permissions', 'admin_permissions.id', '=', 'admin_role_permissions.permission_id')
                ->where('admin_role_permissions.role', $this->role)
                ->pluck('admin_permissions.slug')
                ->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Check if admin has a specific permission
     */
    public function hasPermission($permission)
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions());
    }

    /**
     * Check if admin has any of the given permissions
     */
    public function hasAnyPermission(array $permissions)
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }

        return count(array_intersect($permissions, $this->permissions())) > 0;
    }

    /**
     * Check if admin has all of the given permissions
     */
    public function hasAllPermissions(array $permissions)
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }

        return count(array_intersect($permissions, $this->permissions())) === count($permissions);
    }
}
