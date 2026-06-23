<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\User;
use App\Services\MealPlanningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MealPlanController extends Controller
{
    public function __construct(private readonly MealPlanningService $planner)
    {
    }

    public function index(): View
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $plan = $user->mealPlans()->with('details.food')->latest('week_start')->first();

        return view('meal-plans.index', ['plan' => $plan]);
    }

    public function generate(): RedirectResponse
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $profile = $user->healthProfile;
        if (!$profile) {
            return redirect()->route('health-profiles.index')->with('status', 'Complete health profile first.');
        }

        $payload = $this->planner->generateWeeklyPlan($profile);

        $plan = MealPlan::query()->updateOrCreate(
            ['user_id' => Auth::id(), 'week_start' => $payload['week_start']],
            [
                'target_daily_calories' => $payload['target_daily_calories'],
                'weekly_budget' => $payload['weekly_budget'],
            ],
        );

        $plan->details()->delete();
        $plan->details()->createMany($payload['details']);

        return redirect()->route('meal-plans.index')->with('status', '7-day meal plan generated.');
    }
}
