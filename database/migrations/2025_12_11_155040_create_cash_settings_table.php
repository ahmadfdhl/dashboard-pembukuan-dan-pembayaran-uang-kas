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
        Schema::create('cash_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('weekly_amount', 10, 2)->default(5000);
            $table->string('period')->default('weekly');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('class_id')->constrained();
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_settings');
    }
};
