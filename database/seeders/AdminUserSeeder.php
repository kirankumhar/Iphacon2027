<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        AdminUser::create([
            'username' => 'superadmin',
            'email' => 'superadmin@ismm.com',
            'password_hash' => Hash::make('12345678'),
            'full_name' => 'Super Administrator',
            'role' => 'Super Admin',
            'is_active' => true,
        ]);

        AdminUser::create([
            'username' => 'admin',
            'email' => 'admin@ismm.com',
            'password_hash' => Hash::make('12345678'),
            'full_name' => 'System Administrator',
            'role' => 'Admin',
            'is_active' => true,
        ]);

        AdminUser::create([
            'username' => 'moderator',
            'email' => 'moderator@ismm.com',
            'password_hash' => Hash::make('12345678'),
            'full_name' => 'Content Moderator',
            'role' => 'Moderator',
            'is_active' => true,
        ]);
    }
}
