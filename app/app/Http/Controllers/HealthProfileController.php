<?php

namespace App\Http\Controllers;

use App\Models\HealthProfile;
use App\Services\MealPlanningService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HealthProfileController extends Controller
{
    public function __construct(private readonly MealPlanningService $planner)
    {
    }

    public function index(): View
    {
        $profile = Auth::user()->healthProfile;
        return view('health-profiles.index', ['profile' => $profile]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'gender' => ['required', 'in:male,female,other'],
            'weight_kg' => ['required', 'numeric', 'min:10', 'max:400'],
            'height_cm' => ['required', 'numeric', 'min:50', 'max:250'],
            'medical_condition' => ['required', 'in:none,diabetes,hypertension,obesity'],
            'weekly_budget' => ['required', 'numeric', 'min:100'],
        ]);

        $validated['bmi'] = $this->planner->calculateBmi((float) $validated['weight_kg'], (float) $validated['height_cm']);
        $validated['user_id'] = Auth::id();

        HealthProfile::query()->updateOrCreate(
            ['user_id' => Auth::id()],
            $validated,
        );

        return redirect()->route('health-profiles.index')->with('status', 'Health profile saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}
    public function create() {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
