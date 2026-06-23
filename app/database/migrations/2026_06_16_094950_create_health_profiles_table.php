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
        Schema::create('health_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('age');
            $table->string('gender', 20);
            $table->decimal('weight_kg', 6, 2);
            $table->decimal('height_cm', 6, 2);
            $table->decimal('bmi', 6, 2)->nullable();
            $table->string('medical_condition', 100)->default('none');
            $table->decimal('weekly_budget', 10, 2);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_profiles');
    }
};
