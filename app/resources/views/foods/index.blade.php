<x-app-layout>
    <x-slot name="header">
        <div class="glass-card p-6">
            <h2 class="section-title">Kenya Food Database</h2>
            <p class="section-subtitle">Administrator control for foods, nutritional values, and pricing.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell">
            @if (session('status'))
                <div class="mb-4 glass-card border-emerald-200 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('foods.store') }}" class="glass-card grid gap-3 p-6 sm:grid-cols-4">
                @csrf
                <input name="name" placeholder="Food name" class="rounded-xl border-emerald-200" required>
                <input name="category" placeholder="Category" class="rounded-xl border-emerald-200" required>
                <input type="number" name="calories" placeholder="Calories" class="rounded-xl border-emerald-200" required>
                <input type="number" step="0.1" name="protein_g" placeholder="Protein g" class="rounded-xl border-emerald-200" required>
                <input type="number" step="0.1" name="carbs_g" placeholder="Carbs g" class="rounded-xl border-emerald-200" required>
                <input type="number" step="0.1" name="fats_g" placeholder="Fats g" class="rounded-xl border-emerald-200" required>
                <input type="number" step="0.1" name="fiber_g" placeholder="Fiber g" class="rounded-xl border-emerald-200" required>
                <input type="number" name="glycemic_index" placeholder="Glycemic index" class="rounded-xl border-emerald-200">
                <input type="number" step="0.01" name="cost_per_serving" placeholder="Cost per serving" class="rounded-xl border-emerald-200" required>
                <label class="flex items-center gap-2 rounded-xl border border-emerald-100 bg-white/75 px-3 py-2 text-sm text-emerald-900">
                    <input type="checkbox" name="is_low_sodium" value="1"> Low sodium
                </label>
                <button type="submit" class="brand-btn sm:col-span-4">Add Food</button>
            </form>

            <div class="mt-6 glass-card p-6">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Food</th>
                                <th>Calories</th>
                                <th>Carbs</th>
                                <th>Fiber</th>
                                <th>Cost (KES)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($foods as $food)
                                <tr>
                                    <td>{{ $food->name }}</td>
                                    <td>{{ $food->calories }}</td>
                                    <td>{{ $food->carbs_g }} g</td>
                                    <td>{{ $food->fiber_g }} g</td>
                                    <td>{{ number_format($food->cost_per_serving, 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('foods.destroy', $food->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="brand-btn-secondary">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state my-3">
                                            <div class="empty-state-icon">F</div>
                                            <p class="font-semibold text-emerald-900">No foods found</p>
                                            <p class="mt-1 text-sm text-emerald-800/85">Add foods with nutrition and pricing details to power the planning engine.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $foods->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
