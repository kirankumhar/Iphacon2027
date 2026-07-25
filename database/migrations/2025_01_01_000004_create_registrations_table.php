<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('other_state')->nullable();
            $table->string('pin_code', 20)->nullable();
            $table->string('whatsapp_country_code', 10)->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('dietary_preference')->nullable();
            $table->string('id_proof_type')->nullable();
            $table->string('id_proof_number')->nullable();
            $table->string('id_proof_document_path')->nullable();
            $table->string('delegate_type')->nullable();
            $table->unsignedBigInteger('delegate_category_id')->nullable();
            $table->integer('accompanying_persons')->default(0);
            $table->boolean('participate_in_cme')->default(false);
            $table->string('membership_no')->nullable();
            $table->boolean('is_ismm_member')->default(false);
            $table->string('ismm_membership_no')->nullable();
            $table->boolean('is_isham_member')->default(false);
            $table->string('isham_membership_no')->nullable();
            $table->boolean('is_young_isam_member')->default(false);
            $table->string('young_isam_membership_no')->nullable();
            $table->string('status')->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('registration_pdf_path')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_datetime')->nullable();
            $table->text('revert_reason')->nullable();
            $table->timestamp('reverted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
