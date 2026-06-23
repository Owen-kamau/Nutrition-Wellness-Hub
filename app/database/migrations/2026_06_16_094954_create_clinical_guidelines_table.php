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
        Schema::create('clinical_guidelines', function (Blueprint $table) {
            $table->id();
            $table->string('condition')->unique();
            $table->unsignedInteger('max_daily_carbs')->nullable();
            $table->unsignedInteger('min_daily_fiber')->nullable();
            $table->unsignedInteger('max_daily_sodium')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_guidelines');
    }
};
