<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class NutritionistController extends Controller
{
    public function patients(): View
    {
        $patients = User::role('patient')
            ->with('healthProfile')
            ->withCount('mealLogs')
            ->latest()
            ->paginate(20);

        return view('nutritionist.patients', ['patients' => $patients]);
    }
}
