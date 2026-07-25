<?php
// app/Services/MenuService.php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use App\Models\AdminUser;

class MenuService
{
    public function getUserMenus($user)
    {
        $userRole = $this->getUserRole($user);
        $permissions = $this->getUserPermissions($user);

        return Menu::getMenuTree($userRole, $permissions);
    }

    private function getUserRole($user): string
    {
        if ($user instanceof AdminUser) {
            return strtolower(str_replace(' ', '_', $user->role));
        }

        return 'user';
    }

    private function getUserPermissions($user): array
    {
        // You can implement more complex permission logic here
        $permissions = [];

        if ($user instanceof AdminUser) {
            switch ($user->role) {
                case 'Super Admin':
                    $permissions = ['*']; // All permissions
                    break;
                case 'Admin':
                    $permissions = [
                        'manage_registrations',
                        'manage_payments',
                        'view_reports',
                        'manage_users'
                    ];
                    break;
                case 'Moderator':
                    $permissions = [
                        'view_registrations',
                        'verify_payments',
                        'view_reports'
                    ];
                    break;
            }
        } else {
            $permissions = [
                'view_profile',
                'edit_profile',
                'view_registration',
                'create_registration'
            ];
        }

        return $permissions;
    }
}
