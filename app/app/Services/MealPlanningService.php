<?php

namespace App\Services;

use App\Models\ClinicalGuideline;
use App\Models\Food;
use App\Models\HealthProfile;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MealPlanningService
{
    public function calculateBmi(float $weightKg, float $heightCm): float
    {
        $heightM = $heightCm / 100;
        if ($heightM <= 0) {
            return 0;
        }

        return round($weightKg / ($heightM * $heightM), 2);
    }

    public function calculateDailyCalories(HealthProfile $profile): int
    {
        $base = ($profile->gender === 'female' ? 1400 : 1600) + (int) ($profile->weight_kg * 6);
        if ($profile->bmi >= 30) {
            $base -= 250;
        }

        return max(1200, $base);
    }

    public function generateWeeklyPlan(HealthProfile $profile): array
    {
        $dailyTarget = $this->calculateDailyCalories($profile);
        $weeklyBudget = (float) $profile->weekly_budget;
        $dailyBudget = $weeklyBudget / 7;

        $foods = $this->filterFoodsByGuidelines($profile->medical_condition)
            ->filter(function (Food $food) use ($dailyBudget) {
                return $food->cost_per_serving <= ($dailyBudget / 4);
            })
            ->values();

        if ($foods->count() < 4) {
            $foods = Food::query()->orderBy('fiber_g', 'desc')->limit(12)->get();
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner', 'snack'];
        $details = [];

        foreach ($days as $dayIndex => $dayName) {
            foreach ($mealTypes as $mealIndex => $mealType) {
                $food = $foods[($dayIndex + $mealIndex) % $foods->count()];
                $quantity = $mealType === 'snack' ? 0.8 : 1.2;
                $details[] = [
                    'day_name' => $dayName,
                    'meal_type' => $mealType,
                    'food_id' => $food->id,
                    'quantity' => $quantity,
                    'calories' => (int) round($food->calories * $quantity),
                    'cost' => round($food->cost_per_serving * $quantity, 2),
                ];
            }
        }

        return [
            'week_start' => Carbon::now()->startOfWeek()->toDateString(),
            'target_daily_calories' => $dailyTarget,
            'weekly_budget' => $weeklyBudget,
            'details' => $details,
        ];
    }

    private function filterFoodsByGuidelines(string $condition): Collection
    {
        $query = Food::query()->orderBy('fiber_g', 'desc');
        $condition = strtolower($condition);

        if ($condition === 'diabetes') {
            $guideline = ClinicalGuideline::query()->where('condition', 'diabetes')->first();
            if ($guideline && $guideline->max_daily_carbs) {
                $query->where('carbs_g', '<=', max(15, $guideline->max_daily_carbs / 6));
            }
            $query->where('fiber_g', '>=', 2);
        }

        if ($condition === 'hypertension') {
            $query->where('is_low_sodium', true);
        }

        return $query->limit(25)->get();
    }
}
