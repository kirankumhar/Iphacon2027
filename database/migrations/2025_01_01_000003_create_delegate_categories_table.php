<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delegate_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->decimal('indian_fee', 10, 2)->default(0.00);
            $table->decimal('foreign_fee', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegate_categories');
    }
};
