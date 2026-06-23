<?php

use App\Models\SimpleFormSubmission;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClinicalGuidelineController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HealthProfileController;
use App\Http\Controllers\MealLogController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\NutritionistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/simple-form', function () {
    return view('simple-form');
})->name('simple-form.show');

Route::post('/simple-form', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150'],
        'message' => ['required', 'string', 'max:500'],
    ]);

    SimpleFormSubmission::create($validated);

    return back()->with('success', 'Form submitted successfully for '.$validated['name'].'.');
})->name('simple-form.submit');

Route::middleware(['auth', 'session.timeout'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:patient', 'audit'])->group(function () {
        Route::view('/patient-help/about', 'patient.help.about')->name('patient.help.about');
        Route::view('/patient-help/how-to-use', 'patient.help.how-to-use')->name('patient.help.how-to-use');
        Route::view('/patient-help/faqs', 'patient.help.faqs')->name('patient.help.faqs');
        Route::view('/patient-help/contact', 'patient.help.contact')->name('patient.help.contact');

        Route::get('/health-profiles', [HealthProfileController::class, 'index'])->name('health-profiles.index');
        Route::post('/health-profiles', [HealthProfileController::class, 'store'])->name('health-profiles.store');

        Route::get('/meal-plans', [MealPlanController::class, 'index'])->name('meal-plans.index');
        Route::post('/meal-plans/generate', [MealPlanController::class, 'generate'])->name('meal-plans.generate');

        Route::get('/meal-logs', [MealLogController::class, 'index'])->name('meal-logs.index');
        Route::post('/meal-logs/quick-store', [MealLogController::class, 'quickStore'])->name('meal-logs.quick-store');
        Route::post('/meal-logs', [MealLogController::class, 'store'])->name('meal-logs.store');
        Route::delete('/meal-logs/{id}', [MealLogController::class, 'destroy'])->name('meal-logs.destroy');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    });

    Route::middleware('role:nutritionist')->group(function () {
        Route::get('/nutritionist/patients', [NutritionistController::class, 'patients'])->name('nutritionist.patients');
    });

    Route::middleware(['role:administrator', 'audit'])->group(function () {
        Route::resource('foods', FoodController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('clinical-guidelines', ClinicalGuidelineController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

require __DIR__.'/auth.php';
