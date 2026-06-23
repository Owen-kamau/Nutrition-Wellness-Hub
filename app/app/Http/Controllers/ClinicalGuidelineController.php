<?php

namespace App\Http\Controllers;

use App\Models\ClinicalGuideline;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClinicalGuidelineController extends Controller
{
    public function index(): View
    {
        return view('clinical-guidelines.index', [
            'guidelines' => ClinicalGuideline::query()->orderBy('condition')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'condition' => ['required', 'string', 'max:100'],
            'max_daily_carbs' => ['nullable', 'integer', 'min:1'],
            'min_daily_fiber' => ['nullable', 'integer', 'min:1'],
            'max_daily_sodium' => ['nullable', 'integer', 'min:1'],
            'recommendations' => ['nullable', 'string'],
        ]);

        ClinicalGuideline::query()->create($validated);

        return redirect()->route('clinical-guidelines.index')->with('status', 'Clinical guideline created.');
    }

    /**
     * Display the specified resource.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $guideline = ClinicalGuideline::query()->findOrFail($id);

        $validated = $request->validate([
            'max_daily_carbs' => ['nullable', 'integer', 'min:1'],
            'min_daily_fiber' => ['nullable', 'integer', 'min:1'],
            'max_daily_sodium' => ['nullable', 'integer', 'min:1'],
            'recommendations' => ['nullable', 'string'],
        ]);

        $guideline->update($validated);

        return redirect()->route('clinical-guidelines.index')->with('status', 'Clinical guideline updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        ClinicalGuideline::query()->findOrFail($id)->delete();

        return redirect()->route('clinical-guidelines.index')->with('status', 'Clinical guideline deleted.');
    }

    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
}
