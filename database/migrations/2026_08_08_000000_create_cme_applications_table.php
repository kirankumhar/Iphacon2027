<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cme_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->decimal('cme_fee', 10, 2)->default(2000.00);
            $table->decimal('gst_amount', 10, 2)->default(360.00);
            $table->decimal('total_amount', 10, 2)->default(2360.00);
            $table->string('transaction_id')->nullable();
            $table->string('payment_receipt_path')->nullable();
            $table->enum('status', ['Pending Payment', 'Payment Submitted', 'Approved', 'Rejected'])->default('Pending Payment');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cme_applications');
    }
};
