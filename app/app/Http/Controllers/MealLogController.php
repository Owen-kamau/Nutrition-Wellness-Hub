<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\MealLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MealLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        return view('meal-logs.index', [
            'foods' => Food::query()->orderBy('name')->get(),
            'logs' => $user->mealLogs()->with('food')->latest('consumed_on')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'food_id' => ['required', 'exists:foods,id'],
            'meal_type' => ['required', 'in:breakfast,lunch,dinner,snack'],
            'consumed_on' => ['required', 'date'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
        ]);

        $alreadyLogged = MealLog::query()
            ->where('user_id', Auth::id())
            ->where('food_id', $validated['food_id'])
            ->where('meal_type', $validated['meal_type'])
            ->whereDate('consumed_on', $validated['consumed_on'])
            ->exists();

        if ($alreadyLogged) {
            return redirect()
                ->route('meal-logs.index')
                ->with('status', ucfirst($validated['meal_type']) . ' already logged for this food today.');
        }

        $food = Food::query()->findOrFail($validated['food_id']);
        $validated['user_id'] = Auth::id();
        $validated['calories_consumed'] = (int) round($food->calories * $validated['quantity']);

        MealLog::query()->create($validated);

        return redirect()->route('meal-logs.index')->with('status', 'Meal logged successfully.');
    }

    public function quickStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'food_id' => ['required', 'exists:foods,id'],
            'meal_type' => ['required', 'in:breakfast,lunch,dinner,snack'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
            'consumed_on' => ['nullable', 'date'],
        ]);

        $consumedOn = $validated['consumed_on'] ?? now()->toDateString();

        $alreadyLogged = MealLog::query()
            ->where('user_id', Auth::id())
            ->where('food_id', $validated['food_id'])
            ->where('meal_type', $validated['meal_type'])
            ->whereDate('consumed_on', $consumedOn)
            ->exists();

        if ($alreadyLogged) {
            return redirect()
                ->route('dashboard')
                ->with('status', ucfirst($validated['meal_type']) . ' already logged for this food today.');
        }

        $food = Food::query()->findOrFail($validated['food_id']);

        MealLog::query()->create([
            'user_id' => Auth::id(),
            'food_id' => $validated['food_id'],
            'meal_type' => $validated['meal_type'],
            'quantity' => $validated['quantity'],
            'consumed_on' => $consumedOn,
            'calories_consumed' => (int) round($food->calories * $validated['quantity']),
        ]);

        return redirect()->route('dashboard')->with('status', ucfirst($validated['meal_type']) . ' marked as eaten.');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        MealLog::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id)
            ->delete();

        return redirect()->route('meal-logs.index')->with('status', 'Meal log removed.');
    }

    public function show(string $id) {}
    public function create() {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
}
