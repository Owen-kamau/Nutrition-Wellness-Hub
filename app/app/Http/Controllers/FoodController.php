<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FoodController extends Controller
{
    public function index(): View
    {
        return view('foods.index', [
            'foods' => Food::query()->latest()->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'calories' => ['required', 'integer', 'min:1'],
            'protein_g' => ['required', 'numeric', 'min:0'],
            'carbs_g' => ['required', 'numeric', 'min:0'],
            'fats_g' => ['required', 'numeric', 'min:0'],
            'fiber_g' => ['required', 'numeric', 'min:0'],
            'glycemic_index' => ['nullable', 'integer', 'min:0', 'max:100'],
            'cost_per_serving' => ['required', 'numeric', 'min:0.1'],
            'is_low_sodium' => ['nullable', 'boolean'],
        ]);

        $validated['is_low_sodium'] = $request->boolean('is_low_sodium');

        Food::query()->create($validated);

        return redirect()->route('foods.index')->with('status', 'Food added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $food = Food::query()->findOrFail($id);
        $validated = $request->validate([
            'cost_per_serving' => ['required', 'numeric', 'min:0.1'],
        ]);

        $food->update($validated);

        return redirect()->route('foods.index')->with('status', 'Food price updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Food::query()->findOrFail($id)->delete();
        return redirect()->route('foods.index')->with('status', 'Food removed.');
    }

    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
}
