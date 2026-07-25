<?php
// app/Models/Menu.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'url',
        'icon',
        'permission',
        'roles',
        'parent_id',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'roles' => 'array',
        'is_active' => 'boolean'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    // Check if user has access to this menu
    public function hasAccess($userRole, $permissions = []): bool
    {
        // Check if user role is allowed
        if (!in_array($userRole, $this->roles)) {
            return false;
        }

        // Check specific permission if set
        if ($this->permission && !in_array($this->permission, $permissions)) {
            return false;
        }

        return $this->is_active;
    }

    // Get menu tree for specific role
    public static function getMenuTree($userRole, $permissions = [])
    {
        return self::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(function ($menu) use ($userRole, $permissions) {
                return $menu->hasAccess($userRole, $permissions);
            })
            ->map(function ($menu) use ($userRole, $permissions) {
                $menu->children = $menu->getAccessibleChildren($userRole, $permissions);
                return $menu;
            });
    }

    public function getAccessibleChildren($userRole, $permissions = [])
    {
        return $this->children
            ->filter(function ($child) use ($userRole, $permissions) {
                return $child->hasAccess($userRole, $permissions);
            })
            ->map(function ($child) use ($userRole, $permissions) {
                $child->children = $child->getAccessibleChildren($userRole, $permissions);
                return $child;
            });
    }
}
