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
                'category_name' => 'ISMM Member',
                'indian_fee' => 7000.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'Non-ISMM Member',
                'indian_fee' => 8000.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'Young ISAM Members, PG/Ph.D Students',
                'indian_fee' => 6000.00,
                'foreign_fee' => 175.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'Industry Professional',
                'indian_fee' => 9000.00,
                'foreign_fee' => 200.00,
                'is_active' => true,
            ],
            [
                'category_name' => 'Accompanying Person',
                'indian_fee' => 3000.00,
                'foreign_fee' => 100.00,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            DelegateCategory::create($category);
        }
    }
}
