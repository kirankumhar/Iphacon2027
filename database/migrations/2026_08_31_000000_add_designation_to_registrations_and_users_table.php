<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'designation')) {
                $table->string('designation')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('registrations', 'other_designation')) {
                $table->string('other_designation')->nullable()->after('designation');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'designation')) {
                $table->string('designation')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('users', 'other_designation')) {
                $table->string('other_designation')->nullable()->after('designation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'other_designation')) {
                $table->dropColumn('other_designation');
            }
            if (Schema::hasColumn('registrations', 'designation')) {
                $table->dropColumn('designation');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'other_designation')) {
                $table->dropColumn('other_designation');
            }
            if (Schema::hasColumn('users', 'designation')) {
                $table->dropColumn('designation');
            }
        });
    }
};
