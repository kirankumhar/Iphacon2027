<?php
// database/migrations/update_registrations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Add missing fields for the wizard form
            if (!Schema::hasColumn('registrations', 'city')) {
                $table->string('city')->nullable()->after('state_id');
            }
            if (!Schema::hasColumn('registrations', 'cme_fee')) {
                $table->decimal('cme_fee', 10, 2)->default(0.00)->after('participate_in_cme');
            }
            if (!Schema::hasColumn('registrations', 'step_completed')) {
                $table->integer('step_completed')->default(0)->after('status');
            }
            if (!Schema::hasColumn('registrations', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0.00)->after('cme_fee');
            }
            if (!Schema::hasColumn('registrations', 'delegate_fee')) {
                $table->decimal('delegate_fee', 10, 2)->default(0.00)->after('delegate_category_id');
            }
            if (!Schema::hasColumn('registrations', 'accompanying_fee')) {
                $table->decimal('accompanying_fee', 10, 2)->default(0.00)->after('accompanying_persons');
            }
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['city', 'cme_fee', 'step_completed', 'total_amount', 'delegate_fee', 'accompanying_fee']);
        });
    }
};
