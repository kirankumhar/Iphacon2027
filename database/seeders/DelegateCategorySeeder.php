<?php
// database/seeders/DelegateCategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DelegateCategory;
use Illuminate\Support\Facades\DB;

class DelegateCategorySeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('delegate_categories')->truncate();

        $categories = [
            [
                'category_name' => 'IPHA Member',
                'indian_fee' => 8850.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'Non-IPHA Member',
                'indian_fee' => 17700.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'PG/PhD/MPH Student (Member)',
                'indian_fee' => 7080.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'PG/PhD/MPH Student (Non-Member)',
                'indian_fee' => 9440.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'Co-Delegate / Accompanying Person',
                'indian_fee' => 5900.00,
                'foreign_fee' => 100.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'Foreign Delegates',
                'indian_fee' => 56275.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            DelegateCategory::create($category);
        }
    }
}
