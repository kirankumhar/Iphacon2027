<?php

use App\Models\DelegateCategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const CATEGORIES = [
        [
            'category_name' => 'MBBS (including UG)',
            'indian_fee' => 4000.00,
            'foreign_fee' => 175.00,
            'is_active' => true,
        ],
        [
            'category_name' => 'MPH (Member IPHA)',
            'indian_fee' => 4000.00,
            'foreign_fee' => 175.00,
            'is_active' => true,
        ],
        [
            'category_name' => 'Nutrition and Dietician',
            'indian_fee' => 4000.00,
            'foreign_fee' => 175.00,
            'is_active' => true,
        ],
    ];

    public function up(): void
    {
        foreach (self::CATEGORIES as $category) {
            DelegateCategory::firstOrCreate(
                ['category_name' => $category['category_name']],
                $category
            );
        }
    }

    public function down(): void
    {
        DelegateCategory::whereIn(
            'category_name',
            array_column(self::CATEGORIES, 'category_name')
        )->delete();
    }
};
