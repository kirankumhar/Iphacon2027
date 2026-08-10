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
        Schema::table('abstract_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('abstract_submissions', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('review_comments');
            }
            if (!Schema::hasColumn('abstract_submissions', 'reviewed_by')) {
                $table->string('reviewed_by')->nullable()->after('reviewed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abstract_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('abstract_submissions', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            if (Schema::hasColumn('abstract_submissions', 'reviewed_by')) {
                $table->dropColumn('reviewed_by');
            }
        });
    }
};
