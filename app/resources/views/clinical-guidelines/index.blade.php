<x-app-layout>
    <x-slot name="header">
        <div class="glass-card p-6">
            <h2 class="section-title">Clinical Guidelines Management</h2>
            <p class="section-subtitle">Maintain condition rules for meal engine logic.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell">
            @if (session('status'))
                <div class="mb-4 glass-card border-emerald-200 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('clinical-guidelines.store') }}" class="glass-card grid gap-3 p-6 sm:grid-cols-2">
                @csrf
                <input name="condition" placeholder="Condition (e.g. diabetes)" class="rounded-xl border-emerald-200" required>
                <input type="number" name="max_daily_carbs" placeholder="Max daily carbs" class="rounded-xl border-emerald-200">
                <input type="number" name="min_daily_fiber" placeholder="Min daily fiber" class="rounded-xl border-emerald-200">
                <input type="number" name="max_daily_sodium" placeholder="Max daily sodium" class="rounded-xl border-emerald-200">
                <textarea name="recommendations" rows="3" placeholder="Recommendations" class="rounded-xl border-emerald-200 sm:col-span-2"></textarea>
                <button type="submit" class="brand-btn sm:col-span-2">Add Guideline</button>
            </form>

            <div class="mt-6 grid gap-4">
                @forelse($guidelines as $guideline)
                    <div class="glass-card p-6">
                        <form method="POST" action="{{ route('clinical-guidelines.update', $guideline->id) }}" class="grid gap-3 sm:grid-cols-5">
                            @csrf
                            @method('PATCH')
                            <div class="sm:col-span-1">
                                <p class="text-xs uppercase tracking-[0.15em] text-emerald-700">Condition</p>
                                <p class="mt-1 text-base font-semibold text-emerald-900">{{ ucfirst($guideline->condition) }}</p>
                            </div>
                            <input type="number" name="max_daily_carbs" value="{{ $guideline->max_daily_carbs }}" class="rounded-xl border-emerald-200" placeholder="Max carbs">
                            <input type="number" name="min_daily_fiber" value="{{ $guideline->min_daily_fiber }}" class="rounded-xl border-emerald-200" placeholder="Min fiber">
                            <input type="number" name="max_daily_sodium" value="{{ $guideline->max_daily_sodium }}" class="rounded-xl border-emerald-200" placeholder="Max sodium">
                            <input name="recommendations" value="{{ $guideline->recommendations }}" class="rounded-xl border-emerald-200" placeholder="Recommendations">
                            <div class="flex gap-2 sm:col-span-5">
                                <button type="submit" class="brand-btn">Save</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('clinical-guidelines.destroy', $guideline->id) }}" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="brand-btn-secondary">Delete</button>
                        </form>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">C</div>
                        <p class="font-semibold text-emerald-900">No clinical rules yet</p>
                        <p class="mt-1 text-sm text-emerald-800/85">Create condition-based limits to strengthen the recommendation engine.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
