<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('consent');
            }
        });

        // Backfill existing user photo_path from their latest registration if available
        try {
            DB::statement("
                UPDATE users u
                INNER JOIN (
                    SELECT r1.user_id, r1.photo_path
                    FROM registrations r1
                    INNER JOIN (
                        SELECT user_id, MAX(id) as max_id
                        FROM registrations
                        WHERE photo_path IS NOT NULL AND photo_path != ''
                        GROUP BY user_id
                    ) r2 ON r1.id = r2.max_id
                ) r ON u.id = r.user_id
                SET u.photo_path = r.photo_path
                WHERE u.photo_path IS NULL
            ");
        } catch (\Throwable $e) {
            // Silently continue if database driver or table state doesn't support the raw query
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
        });
    }
};
