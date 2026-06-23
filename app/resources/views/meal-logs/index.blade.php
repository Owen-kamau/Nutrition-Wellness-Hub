<x-app-layout>
    <x-slot name="header">
        <div class="glass-card p-6">
            <h2 class="section-title">Food Logging</h2>
            <p class="section-subtitle">Track consumed meals for adherence and nutritional reporting.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell">
            @if (session('status'))
                <div class="mb-4 glass-card border-emerald-200 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('meal-logs.store') }}" class="glass-card grid gap-4 p-6 sm:grid-cols-5">
                @csrf
                <select name="food_id" class="sm:col-span-2" required>
                    <option value="">Select food</option>
                    @foreach($foods as $food)
                        <option value="{{ $food->id }}">{{ $food->name }}</option>
                    @endforeach
                </select>

                <select name="meal_type" required>
                    @foreach(['breakfast', 'lunch', 'dinner', 'snack'] as $meal)
                        <option value="{{ $meal }}">{{ ucfirst($meal) }}</option>
                    @endforeach
                </select>

                <input type="date" name="consumed_on" value="{{ now()->toDateString() }}" required>
                <input type="number" step="0.1" name="quantity" placeholder="Quantity" required>
                <button type="submit" class="brand-btn sm:col-span-5">Log Meal</button>
            </form>

            <div class="mt-6 glass-card p-6">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Meal</th>
                                <th>Food</th>
                                <th>Quantity</th>
                                <th>Calories</th>
                                <th>Action</th>
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
                                    <td>
                                        <form method="POST" action="{{ route('meal-logs.destroy', $log->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="brand-btn-secondary" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state my-3">
                                            <div class="empty-state-icon">L</div>
                                            <p class="font-semibold text-emerald-900">No meals logged</p>
                                            <p class="mt-1 text-sm text-emerald-800/85">Log your first meal above to start adherence tracking.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $logs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
