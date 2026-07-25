<?php
// database/seeders/StateSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run()
    {
        // Truncate the table first
        DB::table('states')->truncate();

        $states = [
            // All 28 Indian States
            ['country_id' => 1, 'state_code' => 'AP', 'state_name' => 'Andhra Pradesh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'AR', 'state_name' => 'Arunachal Pradesh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'AS', 'state_name' => 'Assam', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'BR', 'state_name' => 'Bihar', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'CG', 'state_name' => 'Chhattisgarh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'GA', 'state_name' => 'Goa', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'GJ', 'state_name' => 'Gujarat', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'HR', 'state_name' => 'Haryana', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'HP', 'state_name' => 'Himachal Pradesh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'JH', 'state_name' => 'Jharkhand', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'KA', 'state_name' => 'Karnataka', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'KL', 'state_name' => 'Kerala', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'MP', 'state_name' => 'Madhya Pradesh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'MH', 'state_name' => 'Maharashtra', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'MN', 'state_name' => 'Manipur', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'ML', 'state_name' => 'Meghalaya', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'MZ', 'state_name' => 'Mizoram', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'NL', 'state_name' => 'Nagaland', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'OR', 'state_name' => 'Odisha', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'PB', 'state_name' => 'Punjab', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'RJ', 'state_name' => 'Rajasthan', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'SK', 'state_name' => 'Sikkim', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'TN', 'state_name' => 'Tamil Nadu', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'TG', 'state_name' => 'Telangana', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'TR', 'state_name' => 'Tripura', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'UP', 'state_name' => 'Uttar Pradesh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'UK', 'state_name' => 'Uttarakhand', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'WB', 'state_name' => 'West Bengal', 'is_active' => 1],

            // Union Territories
            ['country_id' => 1, 'state_code' => 'AN', 'state_name' => 'Andaman and Nicobar Islands', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'CH', 'state_name' => 'Chandigarh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'DH', 'state_name' => 'Dadra and Nagar Haveli and Daman and Diu', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'DL', 'state_name' => 'Delhi', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'JK', 'state_name' => 'Jammu and Kashmir', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'LA', 'state_name' => 'Ladakh', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'LD', 'state_name' => 'Lakshadweep', 'is_active' => 1],
            ['country_id' => 1, 'state_code' => 'PY', 'state_name' => 'Puducherry', 'is_active' => 1],

            // Major international states/provinces for common countries
            // USA States (major ones)
            ['country_id' => 2, 'state_code' => 'AL', 'state_name' => 'Alabama', 'is_active' => 1],
            ['country_id' => 2, 'state_code' => 'AK', 'state_name' => 'Alaska', 'is_active' => 1],
            ['country_id' => 2, 'state_code' => 'AZ', 'state_name' => 'Arizona', 'is_active' => 1],
            ['country_id' => 2, 'state_code' => 'CA', 'state_name' => 'California', 'is_active' => 1],
            ['country_id' => 2, 'state_code' => 'FL', 'state_name' => 'Florida', 'is_active' => 1],
            ['country_id' => 2, 'state_code' => 'NY', 'state_name' => 'New York', 'is_active' => 1],
            ['country_id' => 2, 'state_code' => 'TX', 'state_name' => 'Texas', 'is_active' => 1],

            // UK (major regions)
            ['country_id' => 3, 'state_code' => 'ENG', 'state_name' => 'England', 'is_active' => 1],
            ['country_id' => 3, 'state_code' => 'SCT', 'state_name' => 'Scotland', 'is_active' => 1],
            ['country_id' => 3, 'state_code' => 'WAL', 'state_name' => 'Wales', 'is_active' => 1],
            ['country_id' => 3, 'state_code' => 'NIR', 'state_name' => 'Northern Ireland', 'is_active' => 1],

            // Canada (major provinces)
            ['country_id' => 4, 'state_code' => 'AB', 'state_name' => 'Alberta', 'is_active' => 1],
            ['country_id' => 4, 'state_code' => 'BC', 'state_name' => 'British Columbia', 'is_active' => 1],
            ['country_id' => 4, 'state_code' => 'ON', 'state_name' => 'Ontario', 'is_active' => 1],
            ['country_id' => 4, 'state_code' => 'QC', 'state_name' => 'Quebec', 'is_active' => 1],

            // Australia (states)
            ['country_id' => 5, 'state_code' => 'NSW', 'state_name' => 'New South Wales', 'is_active' => 1],
            ['country_id' => 5, 'state_code' => 'QLD', 'state_name' => 'Queensland', 'is_active' => 1],
            ['country_id' => 5, 'state_code' => 'SA', 'state_name' => 'South Australia', 'is_active' => 1],
            ['country_id' => 5, 'state_code' => 'VIC', 'state_name' => 'Victoria', 'is_active' => 1],
            ['country_id' => 5, 'state_code' => 'WA', 'state_name' => 'Western Australia', 'is_active' => 1],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
