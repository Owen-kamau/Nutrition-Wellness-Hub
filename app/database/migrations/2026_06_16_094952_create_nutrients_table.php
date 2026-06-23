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
        Schema::create('nutrients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('unit', 20);
            $table->timestamps();
        });

        Schema::create('food_nutrient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained('foods')->cascadeOnDelete();
            $table->foreignId('nutrient_id')->constrained()->cascadeOnDelete();
            $table->decimal('value', 10, 2);
            $table->timestamps();

            $table->unique(['food_id', 'nutrient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_nutrient');
        Schema::dropIfExists('nutrients');
    }
};
