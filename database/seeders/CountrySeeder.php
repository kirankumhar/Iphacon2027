<?php
// database/seeders/CountrySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run()
    {
        // Truncate the table first
        DB::table('countries')->truncate();

        $countries = [
            // Major countries first
            ['country_code' => 'IN', 'country_name' => 'India', 'phone_code' => '+91', 'is_active' => true],
            ['country_code' => 'US', 'country_name' => 'United States', 'phone_code' => '+1', 'is_active' => true],
            ['country_code' => 'GB', 'country_name' => 'United Kingdom', 'phone_code' => '+44', 'is_active' => true],
            ['country_code' => 'CA', 'country_name' => 'Canada', 'phone_code' => '+1', 'is_active' => true],
            ['country_code' => 'AU', 'country_name' => 'Australia', 'phone_code' => '+61', 'is_active' => true],

            // Asian countries
            ['country_code' => 'CN', 'country_name' => 'China', 'phone_code' => '+86', 'is_active' => true],
            ['country_code' => 'JP', 'country_name' => 'Japan', 'phone_code' => '+81', 'is_active' => true],
            ['country_code' => 'KR', 'country_name' => 'South Korea', 'phone_code' => '+82', 'is_active' => true],
            ['country_code' => 'SG', 'country_name' => 'Singapore', 'phone_code' => '+65', 'is_active' => true],
            ['country_code' => 'MY', 'country_name' => 'Malaysia', 'phone_code' => '+60', 'is_active' => true],
            ['country_code' => 'TH', 'country_name' => 'Thailand', 'phone_code' => '+66', 'is_active' => true],
            ['country_code' => 'VN', 'country_name' => 'Vietnam', 'phone_code' => '+84', 'is_active' => true],
            ['country_code' => 'ID', 'country_name' => 'Indonesia', 'phone_code' => '+62', 'is_active' => true],
            ['country_code' => 'PH', 'country_name' => 'Philippines', 'phone_code' => '+63', 'is_active' => true],
            ['country_code' => 'BD', 'country_name' => 'Bangladesh', 'phone_code' => '+880', 'is_active' => true],
            ['country_code' => 'LK', 'country_name' => 'Sri Lanka', 'phone_code' => '+94', 'is_active' => true],
            ['country_code' => 'PK', 'country_name' => 'Pakistan', 'phone_code' => '+92', 'is_active' => true],
            ['country_code' => 'NP', 'country_name' => 'Nepal', 'phone_code' => '+977', 'is_active' => true],
            ['country_code' => 'BT', 'country_name' => 'Bhutan', 'phone_code' => '+975', 'is_active' => true],
            ['country_code' => 'MV', 'country_name' => 'Maldives', 'phone_code' => '+960', 'is_active' => true],

            // European countries
            ['country_code' => 'DE', 'country_name' => 'Germany', 'phone_code' => '+49', 'is_active' => true],
            ['country_code' => 'FR', 'country_name' => 'France', 'phone_code' => '+33', 'is_active' => true],
            ['country_code' => 'IT', 'country_name' => 'Italy', 'phone_code' => '+39', 'is_active' => true],
            ['country_code' => 'ES', 'country_name' => 'Spain', 'phone_code' => '+34', 'is_active' => true],
            ['country_code' => 'NL', 'country_name' => 'Netherlands', 'phone_code' => '+31', 'is_active' => true],
            ['country_code' => 'BE', 'country_name' => 'Belgium', 'phone_code' => '+32', 'is_active' => true],
            ['country_code' => 'CH', 'country_name' => 'Switzerland', 'phone_code' => '+41', 'is_active' => true],
            ['country_code' => 'AT', 'country_name' => 'Austria', 'phone_code' => '+43', 'is_active' => true],
            ['country_code' => 'SE', 'country_name' => 'Sweden', 'phone_code' => '+46', 'is_active' => true],
            ['country_code' => 'NO', 'country_name' => 'Norway', 'phone_code' => '+47', 'is_active' => true],
            ['country_code' => 'DK', 'country_name' => 'Denmark', 'phone_code' => '+45', 'is_active' => true],
            ['country_code' => 'FI', 'country_name' => 'Finland', 'phone_code' => '+358', 'is_active' => true],
            ['country_code' => 'RU', 'country_name' => 'Russia', 'phone_code' => '+7', 'is_active' => true],
            ['country_code' => 'PL', 'country_name' => 'Poland', 'phone_code' => '+48', 'is_active' => true],
            ['country_code' => 'CZ', 'country_name' => 'Czech Republic', 'phone_code' => '+420', 'is_active' => true],

            // Middle Eastern countries
            ['country_code' => 'AE', 'country_name' => 'United Arab Emirates', 'phone_code' => '+971', 'is_active' => true],
            ['country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'phone_code' => '+966', 'is_active' => true],
            ['country_code' => 'QA', 'country_name' => 'Qatar', 'phone_code' => '+974', 'is_active' => true],
            ['country_code' => 'KW', 'country_name' => 'Kuwait', 'phone_code' => '+965', 'is_active' => true],
            ['country_code' => 'BH', 'country_name' => 'Bahrain', 'phone_code' => '+973', 'is_active' => true],
            ['country_code' => 'OM', 'country_name' => 'Oman', 'phone_code' => '+968', 'is_active' => true],
            ['country_code' => 'IL', 'country_name' => 'Israel', 'phone_code' => '+972', 'is_active' => true],
            ['country_code' => 'TR', 'country_name' => 'Turkey', 'phone_code' => '+90', 'is_active' => true],
            ['country_code' => 'IR', 'country_name' => 'Iran', 'phone_code' => '+98', 'is_active' => true],

            // African countries
            ['country_code' => 'ZA', 'country_name' => 'South Africa', 'phone_code' => '+27', 'is_active' => true],
            ['country_code' => 'NG', 'country_name' => 'Nigeria', 'phone_code' => '+234', 'is_active' => true],
            ['country_code' => 'EG', 'country_name' => 'Egypt', 'phone_code' => '+20', 'is_active' => true],
            ['country_code' => 'KE', 'country_name' => 'Kenya', 'phone_code' => '+254', 'is_active' => true],
            ['country_code' => 'GH', 'country_name' => 'Ghana', 'phone_code' => '+233', 'is_active' => true],
            ['country_code' => 'MA', 'country_name' => 'Morocco', 'phone_code' => '+212', 'is_active' => true],

            // North American countries
            ['country_code' => 'MX', 'country_name' => 'Mexico', 'phone_code' => '+52', 'is_active' => true],

            // South American countries
            ['country_code' => 'BR', 'country_name' => 'Brazil', 'phone_code' => '+55', 'is_active' => true],
            ['country_code' => 'AR', 'country_name' => 'Argentina', 'phone_code' => '+54', 'is_active' => true],
            ['country_code' => 'CL', 'country_name' => 'Chile', 'phone_code' => '+56', 'is_active' => true],
            ['country_code' => 'CO', 'country_name' => 'Colombia', 'phone_code' => '+57', 'is_active' => true],
            ['country_code' => 'PE', 'country_name' => 'Peru', 'phone_code' => '+51', 'is_active' => true],

            // Oceania
            ['country_code' => 'NZ', 'country_name' => 'New Zealand', 'phone_code' => '+64', 'is_active' => true],
            ['country_code' => 'FJ', 'country_name' => 'Fiji', 'phone_code' => '+679', 'is_active' => true],

            // Additional Asian countries
            ['country_code' => 'HK', 'country_name' => 'Hong Kong', 'phone_code' => '+852', 'is_active' => true],
            ['country_code' => 'TW', 'country_name' => 'Taiwan', 'phone_code' => '+886', 'is_active' => true],
            ['country_code' => 'MO', 'country_name' => 'Macau', 'phone_code' => '+853', 'is_active' => true],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
