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
            if (!Schema::hasColumn('registrations', 'pre_conference_topic')) {
                $table->text('pre_conference_topic')->nullable()->after('participate_in_cme');
            }
        });

        Schema::table('cme_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('cme_applications', 'pre_conference_topic')) {
                $table->text('pre_conference_topic')->nullable()->after('total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'pre_conference_topic')) {
                $table->dropColumn('pre_conference_topic');
            }
        });

        Schema::table('cme_applications', function (Blueprint $table) {
            if (Schema::hasColumn('cme_applications', 'pre_conference_topic')) {
                $table->dropColumn('pre_conference_topic');
            }
        });
    }
};
