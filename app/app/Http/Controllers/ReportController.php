<?php

namespace App\Http\Controllers;

use App\Models\MealLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $logs = MealLog::query()
            ->with('food')
            ->where('user_id', $user->id)
            ->whereBetween('consumed_on', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        $caloriesByDay = [];
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::now()->startOfWeek()->addDays($i);
            $caloriesByDay[$day->format('D')] = (int) $logs
                ->where('consumed_on', $day->toDateString())
                ->sum('calories_consumed');
        }

        return view('reports.index', [
            'logs' => $logs,
            'totalCalories' => (int) $logs->sum('calories_consumed'),
            'totalMeals' => $logs->count(),
            'chartLabels' => array_keys($caloriesByDay),
            'chartValues' => array_values($caloriesByDay),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $logs = MealLog::query()
            ->with('food')
            ->where('user_id', $request->user()->id)
            ->whereBetween('consumed_on', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        $pdf = Pdf::loadView('reports.pdf', ['logs' => $logs]);
        return $pdf->download('nutrition-weekly-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $rows = MealLog::query()
            ->with('food')
            ->where('user_id', $request->user()->id)
            ->whereBetween('consumed_on', [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->map(function (MealLog $log) {
                return [
                    'Date' => $log->consumed_on,
                    'Meal' => $log->meal_type,
                    'Food' => $log->food->name,
                    'Quantity' => $log->quantity,
                    'Calories' => $log->calories_consumed,
                ];
            });

        $export = new class($rows) implements FromCollection {
            public function __construct(private readonly Collection $rows)
            {
            }

            public function collection(): Collection
            {
                return $this->rows;
            }
        };

        return Excel::download($export, 'nutrition-weekly-report.xlsx');
    }
}
