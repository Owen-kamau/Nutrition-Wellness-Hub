<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class MealPlanDetail extends Model
{
    protected $fillable = [
        'meal_plan_id',
        'food_id',
        'day_name',
        'meal_type',
        'quantity',
        'calories',
        'cost',
    ];

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }
}
