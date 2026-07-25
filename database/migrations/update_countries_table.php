<?php
// database/migrations/update_countries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            // Add any missing columns if needed
            if (!Schema::hasColumn('countries', 'phone_code')) {
                $table->string('phone_code', 10)->after('country_name');
            }
            if (!Schema::hasColumn('countries', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('phone_code');
            }
        });
    }

    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['phone_code', 'is_active']);
        });
    }
};
