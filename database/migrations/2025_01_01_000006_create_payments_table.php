<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->decimal('delegate_category_fee', 10, 2)->default(0.00);
            $table->decimal('accompanying_persons_fee', 10, 2)->default(0.00);
            $table->decimal('cme_fee', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('INR');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->text('gateway_response')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('payment_receipt_path')->nullable();
            $table->boolean('admin_verified')->default(false);
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
