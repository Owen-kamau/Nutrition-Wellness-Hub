<?php

namespace Database\Seeders;

use App\Models\ClinicalGuideline;
use App\Models\Food;
use App\Models\HealthProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class NutritionSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foods = [
            ['name' => 'Ugali', 'category' => 'staple', 'calories' => 120, 'protein_g' => 2.6, 'carbs_g' => 25, 'fats_g' => 0.4, 'fiber_g' => 1.2, 'glycemic_index' => 73, 'cost_per_serving' => 25, 'is_low_sodium' => true],
            ['name' => 'Sukuma Wiki', 'category' => 'vegetable', 'calories' => 40, 'protein_g' => 3.0, 'carbs_g' => 7, 'fats_g' => 0.5, 'fiber_g' => 3.5, 'glycemic_index' => 15, 'cost_per_serving' => 20, 'is_low_sodium' => true],
            ['name' => 'Githeri', 'category' => 'mixed', 'calories' => 180, 'protein_g' => 8.0, 'carbs_g' => 30, 'fats_g' => 2.3, 'fiber_g' => 6.0, 'glycemic_index' => 45, 'cost_per_serving' => 35, 'is_low_sodium' => true],
            ['name' => 'Ndengu', 'category' => 'legume', 'calories' => 160, 'protein_g' => 9.0, 'carbs_g' => 24, 'fats_g' => 1.0, 'fiber_g' => 7.0, 'glycemic_index' => 32, 'cost_per_serving' => 30, 'is_low_sodium' => true],
            ['name' => 'Tilapia', 'category' => 'protein', 'calories' => 220, 'protein_g' => 28, 'carbs_g' => 0, 'fats_g' => 12, 'fiber_g' => 0, 'glycemic_index' => null, 'cost_per_serving' => 90, 'is_low_sodium' => true],
            ['name' => 'Brown Rice', 'category' => 'staple', 'calories' => 165, 'protein_g' => 3.6, 'carbs_g' => 34, 'fats_g' => 1.4, 'fiber_g' => 1.8, 'glycemic_index' => 50, 'cost_per_serving' => 45, 'is_low_sodium' => true],
            ['name' => 'Avocado', 'category' => 'fruit', 'calories' => 130, 'protein_g' => 1.6, 'carbs_g' => 6, 'fats_g' => 12, 'fiber_g' => 5.0, 'glycemic_index' => 10, 'cost_per_serving' => 30, 'is_low_sodium' => true],
            ['name' => 'Banana', 'category' => 'fruit', 'calories' => 105, 'protein_g' => 1.3, 'carbs_g' => 27, 'fats_g' => 0.3, 'fiber_g' => 3.1, 'glycemic_index' => 51, 'cost_per_serving' => 15, 'is_low_sodium' => true],
        ];

        foreach ($foods as $food) {
            Food::query()->updateOrCreate(['name' => $food['name']], $food);
        }

        ClinicalGuideline::query()->updateOrCreate(
            ['condition' => 'diabetes'],
            ['max_daily_carbs' => 180, 'min_daily_fiber' => 25, 'max_daily_sodium' => 2000, 'recommendations' => 'Reduce simple sugars, increase fiber, control carbohydrates.'],
        );

        ClinicalGuideline::query()->updateOrCreate(
            ['condition' => 'hypertension'],
            ['max_daily_carbs' => 220, 'min_daily_fiber' => 30, 'max_daily_sodium' => 1500, 'recommendations' => 'Prefer low sodium foods and high potassium vegetables.'],
        );

        ClinicalGuideline::query()->updateOrCreate(
            ['condition' => 'obesity'],
            ['max_daily_carbs' => 200, 'min_daily_fiber' => 30, 'max_daily_sodium' => 2000, 'recommendations' => 'Calorie deficit and high satiety meals.'],
        );

        $patient = User::query()->where('email', 'patient@nutrition.local')->first();
        if ($patient) {
            HealthProfile::query()->updateOrCreate(
                ['user_id' => $patient->id],
                [
                    'age' => 34,
                    'gender' => 'male',
                    'weight_kg' => 84,
                    'height_cm' => 173,
                    'bmi' => 28.07,
                    'medical_condition' => 'diabetes',
                    'weekly_budget' => 4500,
                ],
            );
        }
    }
}
