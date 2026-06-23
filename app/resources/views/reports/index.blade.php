<x-app-layout>
    <x-slot name="header">
        <div class="glass-card p-6">
            <h2 class="section-title">Nutrition Reports</h2>
            <p class="section-subtitle">Weekly nutritional analysis and export center.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell max-w-5xl">
            <div class="mb-4 flex flex-wrap gap-3">
                <a href="{{ route('reports.pdf') }}" class="brand-btn">Export PDF</a>
                <a href="{{ route('reports.excel') }}" class="brand-btn-secondary">Export Excel</a>
            </div>

            <div class="glass-card p-6">
                <div class="mb-4 grid gap-4 sm:grid-cols-2">
                    <div class="stat-card">
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-700">Total Meals</p>
                        <p class="text-3xl font-bold text-emerald-900">{{ $totalMeals }}</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-700">Total Calories</p>
                        <p class="text-3xl font-bold text-emerald-900">{{ $totalCalories }}</p>
                    </div>
                </div>

                <div class="mb-6 simple-chart" data-simple-chart data-labels='@json($chartLabels ?? [])' data-values='@json($chartValues ?? [])'>
                    <p class="text-xs uppercase tracking-[0.15em] text-emerald-700">Calories Trend</p>
                    <h3 class="mt-2 text-lg font-bold text-emerald-900">Daily Intake This Week</h3>
                    <div class="mt-4 simple-chart-grid" data-chart-grid></div>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Meal</th>
                                <th>Food</th>
                                <th>Quantity</th>
                                <th>Calories</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->consumed_on }}</td>
                                    <td>{{ ucfirst($log->meal_type) }}</td>
                                    <td>{{ $log->food->name }}</td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>{{ $log->calories_consumed }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state my-3">
                                            <div class="empty-state-icon">R</div>
                                            <p class="font-semibold text-emerald-900">No report data yet</p>
                                            <p class="mt-1 text-sm text-emerald-800/85">Start logging meals to generate weekly analytics and export-ready reports.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
