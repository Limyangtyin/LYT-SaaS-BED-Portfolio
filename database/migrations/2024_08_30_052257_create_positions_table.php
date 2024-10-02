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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('advertising_start_date');
            $table->timestamp('advertising_end_date');
            $table->string('position_title')->index('title');
            $table->string('position_description')->index('description');
            $table->string('position_keywords')->index('keyword');
            $table->float('minimum_salary');
            $table->float('maximum_salary');
            $table->string('salary_currency')->default('AUD');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('benefits')->nullable();
            $table->string('requirements')->nullable();
            $table->enum('position_type', ['permanent', 'contract', 'part-time', 'casual', 'internship']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
