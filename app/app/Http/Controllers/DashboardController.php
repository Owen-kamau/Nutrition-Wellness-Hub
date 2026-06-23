<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\MealLog;
use App\Models\MealPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        if ($user->hasRole('administrator')) {
            $stats = [
                'users' => User::count(),
                'foods' => Food::count(),
                'mealPlans' => MealPlan::count(),
                'mealLogs' => MealLog::count(),
            ];

            return view('dashboard', [
                'role' => 'administrator',
                'stats' => $stats,
                'chartTitle' => 'System Distribution',
                'chartLabels' => array_map(fn ($label) => (string) str($label)->headline(), array_keys($stats)),
                'chartValues' => array_values($stats),
            ]);
        }

        if ($user->hasRole('nutritionist')) {
            $stats = [
                'patients' => User::role('patient')->count(),
                'plansThisWeek' => MealPlan::query()->whereBetween('week_start', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'highRiskPatients' => User::role('patient')->whereHas('healthProfile', function ($query) {
                    $query->whereIn('medical_condition', ['diabetes', 'hypertension', 'obesity']);
                })->count(),
                'logsToday' => MealLog::query()->whereDate('consumed_on', now()->toDateString())->count(),
            ];

            return view('dashboard', [
                'role' => 'nutritionist',
                'stats' => $stats,
                'chartTitle' => 'Care Monitoring Snapshot',
                'chartLabels' => array_map(fn ($label) => (string) str($label)->headline(), array_keys($stats)),
                'chartValues' => array_values($stats),
            ]);
        }

        $today = Carbon::now();
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        $monthStart = $today->copy()->startOfMonth();

        $plans = $user->mealPlans()
            ->with('details.food')
            ->whereDate('week_start', '<=', $today->toDateString())
            ->whereDate('week_start', '>=', $monthStart->copy()->subDays(7)->toDateString())
            ->orderByDesc('week_start')
            ->get();

        $planForDate = static function (Carbon $date) use ($plans): ?MealPlan {
            return $plans->first(function (MealPlan $plan) use ($date) {
                $start = Carbon::parse((string) $plan->week_start)->startOfDay();
                $end = $start->copy()->addDays(6)->endOfDay();

                return $date->between($start, $end);
            });
        };

        $weeklyPlan = $planForDate($today);

        $matchesDay = static function (?string $dayName, Carbon $date): bool {
            if (!$dayName) {
                return false;
            }

            $normalized = strtolower(trim($dayName));
            return in_array($normalized, [
                strtolower($date->format('l')),
                strtolower($date->format('D')),
            ], true);
        };

        $weekLogs = $user->mealLogs()
            ->with('food')
            ->whereBetween('consumed_on', [$weekStart, $weekEnd])
            ->get();

        $todayLogs = $user->mealLogs()
            ->with('food')
            ->whereDate('consumed_on', $today->toDateString())
            ->get();

        $monthLogs = $user->mealLogs()
            ->with('food')
            ->whereBetween('consumed_on', [$monthStart, $today->copy()->endOfDay()])
            ->get();

        $weekBudgetSpent = (float) $weekLogs->sum(function (MealLog $log) {
            return ($log->food->cost_per_serving ?? 0) * $log->quantity;
        });

        $mealTypes = [
            'breakfast',
            'lunch',
            'snack',
            'dinner',
        ];

        $mealCutoffHours = [
            'breakfast' => 10,
            'lunch' => 15,
            'snack' => 18,
            'dinner' => 23,
        ];

        $expectedMealTypesForDate = function (Carbon $date) use ($planForDate, $matchesDay, $mealCutoffHours): array {
            $plan = $planForDate($date);
            if (!$plan) {
                return [];
            }

            $types = $plan->details
                ->filter(fn ($detail) => $matchesDay((string) $detail->day_name, $date))
                ->pluck('meal_type')
                ->map(fn ($type) => strtolower((string) $type))
                ->unique()
                ->values();

            if ($date->isToday()) {
                $currentHour = (int) now()->format('G');
                $types = $types->filter(fn ($type) => $currentHour >= ($mealCutoffHours[$type] ?? 24))->values();
            }

            return $types->all();
        };

        $mealTypesLoggedByDate = $monthLogs
            ->groupBy(fn (MealLog $log) => (string) $log->consumed_on)
            ->map(fn ($logs) => $logs->pluck('meal_type')->map(fn ($type) => strtolower((string) $type))->unique()->values());

        $calculateAdherenceForRange = function (Carbon $startDate, Carbon $endDate) use ($expectedMealTypesForDate, $mealTypesLoggedByDate): array {
            $expectedCount = 0;
            $followedCount = 0;
            $daysCompleted = 0;

            $cursor = $startDate->copy()->startOfDay();
            $end = $endDate->copy()->startOfDay();

            while ($cursor->lte($end)) {
                $expectedTypes = collect($expectedMealTypesForDate($cursor));
                $loggedTypes = collect($mealTypesLoggedByDate->get($cursor->toDateString(), collect()));

                $dayExpectedCount = $expectedTypes->count();
                $dayFollowedCount = $expectedTypes->intersect($loggedTypes)->count();

                $expectedCount += $dayExpectedCount;
                $followedCount += $dayFollowedCount;

                if ($dayExpectedCount > 0 && $dayFollowedCount >= $dayExpectedCount) {
                    $daysCompleted++;
                }

                $cursor->addDay();
            }

            $percent = $expectedCount > 0
                ? (int) round(($followedCount / $expectedCount) * 100)
                : 0;

            return [
                'percent' => $percent,
                'expected' => $expectedCount,
                'followed' => $followedCount,
                'missed' => max(0, $expectedCount - $followedCount),
                'daysCompleted' => $daysCompleted,
            ];
        };

        $todayPlanDetails = $weeklyPlan
            ? $weeklyPlan->details->filter(fn ($detail) => $matchesDay((string) $detail->day_name, $today))
            : collect();

        $todayLoggedTypes = $todayLogs->pluck('meal_type')
            ->map(fn ($mealType) => strtolower((string) $mealType))
            ->unique()
            ->values();

        $todayMealPlan = [];
        foreach ($mealTypes as $mealType) {
            $detail = $todayPlanDetails->firstWhere('meal_type', $mealType);
            $food = $detail?->food;
            $quantity = (float) ($detail?->quantity ?? 1);
            $cost = (float) ($detail?->cost ?? (($food?->cost_per_serving ?? 0) * $quantity));
            $calories = (int) round($detail?->calories ?? (($food?->calories ?? 0) * $quantity));

            $todayMealPlan[] = [
                'meal_type' => $mealType,
                'title' => ucfirst($mealType),
                'has_plan' => (bool) $detail,
                'is_logged' => $todayLoggedTypes->contains($mealType),
                'food_id' => $food?->id,
                'food_name' => $food?->name ?? 'No meal assigned',
                'quantity' => $quantity,
                'cost' => $cost,
                'calories' => $calories,
                'protein_g' => round(($food?->protein_g ?? 0) * $quantity, 1),
                'carbs_g' => round(($food?->carbs_g ?? 0) * $quantity, 1),
                'fats_g' => round(($food?->fats_g ?? 0) * $quantity, 1),
            ];
        }

        $weeklyAdherenceData = $calculateAdherenceForRange($weekStart, $today->copy()->min($weekEnd));
        $monthlyAdherenceData = $calculateAdherenceForRange($monthStart, $today);
        $dailyAdherenceData = $calculateAdherenceForRange($today, $today);

        $adherenceDaily = $dailyAdherenceData['percent'];
        $adherenceWeekly = $weeklyAdherenceData['percent'];
        $adherenceMonthly = $monthlyAdherenceData['percent'];

        $plannedMealsWeek = $weeklyAdherenceData['expected'];
        $loggedMealsWeek = $weeklyAdherenceData['followed'];
        $mealsMissed = $weeklyAdherenceData['missed'];
        $daysCompleted = $weeklyAdherenceData['daysCompleted'];

        $weeklyBudget = (float) ($weeklyPlan?->weekly_budget ?? $user->healthProfile?->weekly_budget ?? 0);
        $remainingBudget = (float) round($weeklyBudget - $weekBudgetSpent, 2);

        $fiberByDay = [];
        foreach ($weekLogs as $log) {
            $date = (string) $log->consumed_on;
            $fiberByDay[$date] = ($fiberByDay[$date] ?? 0) + (($log->food->fiber_g ?? 0) * $log->quantity);
        }
        $fiberGoalDays = collect($fiberByDay)->filter(fn ($fiber) => $fiber >= 25)->count();

        $weeklyCarbs = (float) $weekLogs->sum(fn (MealLog $log) => ($log->food->carbs_g ?? 0) * $log->quantity);
        $avgDailyCarbs = $weeklyCarbs / 7;
        $targetDailyCalories = (int) ($weeklyPlan?->target_daily_calories ?? 2000);
        $targetDailyCarbs = ($targetDailyCalories * 0.5) / 4;
        $carbDeltaPct = $targetDailyCarbs > 0
            ? (int) round((($avgDailyCarbs - $targetDailyCarbs) / $targetDailyCarbs) * 100)
            : 0;

        $vegetableServings = (float) $weekLogs
            ->filter(fn (MealLog $log) => str_contains(strtolower((string) ($log->food->category ?? '')), 'vegetable'))
            ->sum('quantity');

        $nutritionInsights = [
            "You met your fiber goal {$fiberGoalDays} days this week.",
            $carbDeltaPct >= 0
                ? "Your carbohydrate intake is {$carbDeltaPct}% above target."
                : "Your carbohydrate intake is " . abs($carbDeltaPct) . "% below target.",
            $weeklyBudget > 0
                ? ($weekBudgetSpent <= $weeklyBudget
                    ? 'You stayed within budget this week.'
                    : 'Your spending is above this week\'s budget target.')
                : 'Set a weekly budget in your profile to activate budget insights.',
            $vegetableServings < 7
                ? 'Adding more vegetables can improve your meal balance.'
                : 'Great vegetable consistency this week. Keep it up.',
        ];

        $totalWeekMeals = max(1, $loggedMealsWeek);
        $lowGiMeals = $weekLogs->filter(fn (MealLog $log) => ($log->food->glycemic_index ?? 100) <= 55)->count();
        $lowGiProgress = (int) round(($lowGiMeals / $totalWeekMeals) * 100);

        $vegGoalProgress = (int) min(100, round(($vegetableServings / 14) * 100));
        $avgDailyCalories = (float) $weekLogs->sum('calories_consumed') / 7;
        $calorieVariancePct = $targetDailyCalories > 0
            ? abs(($avgDailyCalories - $targetDailyCalories) / $targetDailyCalories) * 100
            : 0;
        $calorieGoalProgress = (int) max(0, 100 - min(100, round($calorieVariancePct)));

        $bmi = (float) ($user->healthProfile?->bmi ?? 0);
        if ($bmi <= 0) {
            $weightGoalProgress = 0;
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $weightGoalProgress = 100;
        } else {
            $weightGoalProgress = (int) max(25, 100 - min(75, round(abs($bmi - 22) * 8)));
        }

        $healthGoals = [
            [
                'title' => 'Reduce sugar intake',
                'progress' => $lowGiProgress,
                'note' => 'Based on low glycemic meal choices this week.',
            ],
            [
                'title' => 'Increase vegetable servings',
                'progress' => $vegGoalProgress,
                'note' => 'Target: at least 2 servings per day.',
            ],
            [
                'title' => 'Maintain calorie target',
                'progress' => $calorieGoalProgress,
                'note' => "Average daily calories: " . number_format($avgDailyCalories) . ".",
            ],
            [
                'title' => 'Weight management goal',
                'progress' => $weightGoalProgress,
                'note' => $bmi > 0 ? "Current BMI: {$bmi}." : 'Complete profile to track BMI progress.',
            ],
        ];

        $previousWeekStart = $weekStart->copy()->subWeek();
        $previousWeekEnd = $weekEnd->copy()->subWeek();
        $previousWeekLogs = $user->mealLogs()
            ->with('food')
            ->whereBetween('consumed_on', [$previousWeekStart, $previousWeekEnd])
            ->get();
        $currentProtein = (float) $weekLogs->sum(fn (MealLog $log) => ($log->food->protein_g ?? 0) * $log->quantity);
        $previousProtein = (float) $previousWeekLogs->sum(fn (MealLog $log) => ($log->food->protein_g ?? 0) * $log->quantity);
        $proteinChangePct = $previousProtein > 0
            ? (int) round((($currentProtein - $previousProtein) / $previousProtein) * 100)
            : 0;

        $personalizedRecommendations = [
            'Try replacing white bread with sweet potatoes for steadier blood sugar support.',
            $remainingBudget > ($weeklyBudget * 0.3)
                ? "You're spending less than planned. Consider adding fruits to improve nutrient diversity."
                : 'You are near your budget limit. Focus on affordable high-fiber local foods.',
            $proteinChangePct >= 0
                ? "Your protein intake has improved by {$proteinChangePct}% this week."
                : 'Your protein intake is trending down. Add beans, eggs, or lean fish to meals.',
        ];

        $notifications = [];
        if (!$todayLoggedTypes->contains('breakfast') && $today->hour >= 9) {
            $notifications[] = "You haven't logged breakfast today.";
        }
        if (!$todayLoggedTypes->contains('lunch') && $today->hour >= 12 && $today->hour < 15) {
            $notifications[] = 'Time for lunch.';
        }
        if ($weeklyPlan) {
            $notifications[] = 'Weekly meal plan ready.';
        } else {
            $notifications[] = 'Generate your weekly meal plan to activate recommendations.';
        }

        $caloriesByDay = [];
        $adherenceTrend = [];
        $budgetTrend = [];
        $trendLabels = [];
        $cumulativeSpent = 0.0;

        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $dayLabel = $day->format('D');
            $trendLabels[] = $dayLabel;

            $dayLogs = $weekLogs->where('consumed_on', $day->toDateString());
            $dayCalories = (int) $dayLogs->sum('calories_consumed');
            $daySpent = (float) $dayLogs->sum(fn (MealLog $log) => ($log->food->cost_per_serving ?? 0) * $log->quantity);
            $cumulativeSpent += $daySpent;

            $dayPlannedCount = $weeklyPlan
                ? $weeklyPlan->details->filter(fn ($detail) => $matchesDay((string) $detail->day_name, $day))->count()
                : 0;
            $dayAdherence = $dayPlannedCount > 0
                ? (int) round(min(100, ($dayLogs->count() / $dayPlannedCount) * 100))
                : 0;

            $caloriesByDay[$dayLabel] = $dayCalories;
            $adherenceTrend[] = $dayAdherence;
            $budgetTrend[] = (float) round($cumulativeSpent, 2);
        }

        return view('dashboard', [
            'role' => 'patient',
            'stats' => [
                'dailyCalories' => (int) $todayLogs->sum('calories_consumed'),
                'budgetSpent' => (float) round($weekBudgetSpent, 2),
                'adherence' => $adherenceWeekly,
                'plannedMeals' => $plannedMealsWeek,
            ],
            'chartTitle' => 'Weekly Calories Trend',
            'chartLabels' => array_keys($caloriesByDay),
            'chartValues' => array_values($caloriesByDay),
            'todayMealPlan' => $todayMealPlan,
            'nutritionInsights' => $nutritionInsights,
            'adherencePerformance' => [
                'daily' => $adherenceDaily,
                'weekly' => $adherenceWeekly,
                'monthly' => $adherenceMonthly,
                'followed' => $loggedMealsWeek,
                'missed' => $mealsMissed,
                'daysCompleted' => $daysCompleted,
                'dailyFollowed' => $dailyAdherenceData['followed'],
                'dailyExpected' => $dailyAdherenceData['expected'],
                'weeklyFollowed' => $weeklyAdherenceData['followed'],
                'weeklyExpected' => $weeklyAdherenceData['expected'],
                'monthlyFollowed' => $monthlyAdherenceData['followed'],
                'monthlyExpected' => $monthlyAdherenceData['expected'],
            ],
            'budgetTracker' => [
                'weeklyBudget' => $weeklyBudget,
                'spent' => (float) round($weekBudgetSpent, 2),
                'remaining' => (float) round($remainingBudget, 2),
            ],
            'healthGoals' => $healthGoals,
            'personalizedRecommendations' => $personalizedRecommendations,
            'notifications' => $notifications,
            'trendLabels' => $trendLabels,
            'adherenceTrend' => $adherenceTrend,
            'budgetTrend' => $budgetTrend,
            'calorieTrend' => array_values($caloriesByDay),
        ]);
    }
}
