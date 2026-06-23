<x-app-layout>
    <x-slot name="header">
        <div class="glass-card p-6">
            <h2 class="section-title">7-Day Meal Planning Engine</h2>
            <p class="section-subtitle">Generate budget-aware nutrition plans aligned with medical conditions.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell">
            @if (session('status'))
                <div class="mb-4 glass-card border-emerald-200 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('meal-plans.generate') }}">
                @csrf
                <button type="submit" class="brand-btn">Regenerate 7-Day Plan</button>
            </form>

            @if($plan)
                <div class="mt-6 glass-card p-6">
                    <div class="mb-6 grid gap-4 sm:grid-cols-3">
                        <div class="stat-card">
                            <p class="text-xs uppercase tracking-[0.15em] text-emerald-700">Week Start</p>
                            <p class="mt-2 text-xl font-bold text-emerald-950">{{ $plan->week_start }}</p>
                        </div>
                        <div class="stat-card">
                            <p class="text-xs uppercase tracking-[0.15em] text-emerald-700">Target Daily Calories</p>
                            <p class="mt-2 text-xl font-bold text-emerald-950">{{ number_format($plan->target_daily_calories) }}</p>
                        </div>
                        <div class="stat-card">
                            <p class="text-xs uppercase tracking-[0.15em] text-emerald-700">Weekly Budget</p>
                            <p class="mt-2 text-xl font-bold text-emerald-950">KES {{ number_format($plan->weekly_budget, 2) }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Meal</th>
                                    <th>Food</th>
                                    <th>Qty</th>
                                    <th>Calories</th>
                                    <th>Cost (KES)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plan->details as $detail)
                                    <tr>
                                        <td>{{ $detail->day_name }}</td>
                                        <td>{{ ucfirst($detail->meal_type) }}</td>
                                        <td>{{ $detail->food->name }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ $detail->calories }}</td>
                                        <td>{{ number_format($detail->cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="mt-6 empty-state">
                    <div class="empty-state-icon">P</div>
                    <p class="font-semibold text-emerald-900">No meal plan generated</p>
                    <p class="mt-1 text-sm text-emerald-800/85">Generate your first 7-day plan to see structured breakfast, lunch, dinner, and snacks.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
