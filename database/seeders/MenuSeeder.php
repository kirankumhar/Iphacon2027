<?php
// database/seeders/MenuSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            // User Menus
            [
                'title' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'roles' => ['user', 'admin', 'super_admin', 'moderator'],
                'sort_order' => 1
            ],
            [
                'title' => 'My Profile',
                'url' => '/profile',
                'icon' => 'fas fa-user',
                'roles' => ['user'],
                'sort_order' => 2
            ],
            [
                'title' => 'Registration',
                'url' => '/registration',
                'icon' => 'fas fa-edit',
                'roles' => ['user'],
                'sort_order' => 3
            ],

            // Admin Menus
            [
                'title' => 'User Management',
                'url' => null,
                'icon' => 'fas fa-users',
                'roles' => ['admin', 'super_admin'],
                'sort_order' => 4,
                'children' => [
                    [
                        'title' => 'All Users',
                        'url' => '/admin/users',
                        'icon' => 'fas fa-list',
                        'roles' => ['admin', 'super_admin'],
                        'permission' => 'manage_users'
                    ],
                    [
                        'title' => 'Add User',
                        'url' => '/admin/users/create',
                        'icon' => 'fas fa-plus',
                        'roles' => ['admin', 'super_admin'],
                        'permission' => 'manage_users'
                    ]
                ]
            ],
            [
                'title' => 'Registration Management',
                'url' => null,
                'icon' => 'fas fa-clipboard-list',
                'roles' => ['admin', 'super_admin', 'moderator'],
                'sort_order' => 5,
                'children' => [
                    [
                        'title' => 'All Registrations',
                        'url' => '/admin/registrations',
                        'icon' => 'fas fa-list',
                        'roles' => ['admin', 'super_admin', 'moderator'],
                        'permission' => 'view_registrations'
                    ],
                    [
                        'title' => 'Pending Approvals',
                        'url' => '/admin/registrations/pending',
                        'icon' => 'fas fa-clock',
                        'roles' => ['admin', 'super_admin', 'moderator'],
                        'permission' => 'view_registrations'
                    ]
                ]
            ],
            [
                'title' => 'Payment Management',
                'url' => null,
                'icon' => 'fas fa-credit-card',
                'roles' => ['admin', 'super_admin', 'moderator'],
                'sort_order' => 6,
                'children' => [
                    [
                        'title' => 'All Payments',
                        'url' => '/admin/payments',
                        'icon' => 'fas fa-list',
                        'roles' => ['admin', 'super_admin', 'moderator'],
                        'permission' => 'view_payments'
                    ],
                    [
                        'title' => 'Verify Payments',
                        'url' => '/admin/payments/verify',
                        'icon' => 'fas fa-check',
                        'roles' => ['admin', 'super_admin', 'moderator'],
                        'permission' => 'verify_payments'
                    ]
                ]
            ],
            [
                'title' => 'Reports',
                'url' => null,
                'icon' => 'fas fa-chart-bar',
                'roles' => ['admin', 'super_admin', 'moderator'],
                'sort_order' => 7,
                'children' => [
                    [
                        'title' => 'Registration Report',
                        'url' => '/admin/reports/registrations',
                        'icon' => 'fas fa-file-alt',
                        'roles' => ['admin', 'super_admin', 'moderator'],
                        'permission' => 'view_reports'
                    ],
                    [
                        'title' => 'Payment Report',
                        'url' => '/admin/reports/payments',
                        'icon' => 'fas fa-file-invoice-dollar',
                        'roles' => ['admin', 'super_admin', 'moderator'],
                        'permission' => 'view_reports'
                    ]
                ]
            ],
            [
                'title' => 'System Settings',
                'url' => null,
                'icon' => 'fas fa-cogs',
                'roles' => ['super_admin'],
                'sort_order' => 8,
                'children' => [
                    [
                        'title' => 'Menu Management',
                        'url' => '/admin/menus',
                        'icon' => 'fas fa-bars',
                        'roles' => ['super_admin'],
                        'permission' => 'manage_system'
                    ],
                    [
                        'title' => 'Admin Users',
                        'url' => '/admin/admin-users',
                        'icon' => 'fas fa-user-shield',
                        'roles' => ['super_admin'],
                        'permission' => 'manage_system'
                    ]
                ]
            ]
        ];

        $this->createMenus($menus);
    }

    private function createMenus($menus, $parentId = null)
    {
        foreach ($menus as $menu) {
            $children = $menu['children'] ?? [];
            unset($menu['children']);

            $menu['parent_id'] = $parentId;
            $createdMenu = Menu::create($menu);

            if (!empty($children)) {
                $this->createMenus($children, $createdMenu->id);
            }
        }
    }
}
