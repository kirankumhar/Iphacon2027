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
        Schema::create('abstract_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->string('acknowledgement_id')->unique()->nullable();
            $table->string('presenting_author_name')->nullable();
            $table->string('presenting_author_designation')->nullable();
            $table->string('presenting_author_department')->nullable();
            $table->string('presenting_author_institution')->nullable();
            $table->string('presenting_author_city')->nullable();
            $table->string('presenting_author_state')->nullable();
            $table->string('presenting_author_country')->nullable();
            $table->string('presenting_author_email')->nullable();
            $table->string('presenting_author_mobile')->nullable();
            $table->string('medical_council_reg_no')->nullable();
            $table->json('co_authors')->nullable();
            $table->string('presentation_mode')->nullable();
            $table->string('presenter_category')->nullable();
            $table->string('other_category_text')->nullable();
            $table->string('conference_theme')->nullable();
            $table->text('abstract_title')->nullable();
            $table->text('keywords')->nullable();
            $table->longText('abstract_background')->nullable();
            $table->longText('abstract_objectives')->nullable();
            $table->longText('abstract_methodology')->nullable();
            $table->longText('abstract_results')->nullable();
            $table->longText('abstract_conclusion')->nullable();
            $table->integer('total_word_count')->default(0);
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('Draft');
            $table->timestamp('submitted_at')->nullable();
            $table->text('review_comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abstract_submissions');
    }
};
