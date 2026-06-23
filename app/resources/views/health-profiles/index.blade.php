<x-app-layout>
    <x-slot name="header">
        <div class="glass-card p-6">
            <h2 class="section-title">Health Profile Management</h2>
            <p class="section-subtitle">Capture personal, physical, medical, and economic data.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell max-w-5xl">
            @if (session('status'))
                <div class="mb-4 glass-card border-emerald-200 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            <div class="grid gap-4 lg:grid-cols-[1.5fr_1fr]">
                <form method="POST" action="{{ route('health-profiles.store') }}" class="glass-card space-y-5 p-6">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-emerald-900">Age</label>
                            <input type="number" name="age" value="{{ old('age', $profile->age ?? '') }}" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-emerald-900">Gender</label>
                            <select name="gender" required>
                                @foreach(['male', 'female', 'other'] as $option)
                                    <option value="{{ $option }}" @selected(old('gender', $profile->gender ?? '') === $option)>{{ ucfirst($option) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-emerald-900">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $profile->weight_kg ?? '') }}" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-emerald-900">Height (cm)</label>
                            <input type="number" step="0.1" name="height_cm" value="{{ old('height_cm', $profile->height_cm ?? '') }}" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-emerald-900">Medical Condition</label>
                            <select name="medical_condition" required>
                                @foreach(['none', 'diabetes', 'hypertension', 'obesity'] as $condition)
                                    <option value="{{ $condition }}" @selected(old('medical_condition', $profile->medical_condition ?? '') === $condition)>{{ ucfirst($condition) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-emerald-900">Weekly Food Budget (KES)</label>
                            <input type="number" step="0.01" name="weekly_budget" value="{{ old('weekly_budget', $profile->weekly_budget ?? '') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="brand-btn">Save Health Profile</button>
                </form>

                <aside class="glass-card p-6">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-700">Health Snapshot</p>
                    <div class="mt-4 space-y-2 text-sm text-emerald-900">
                        <p><span class="font-semibold">Current BMI:</span> {{ $profile->bmi ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Condition:</span> {{ ucfirst($profile->medical_condition ?? 'none') }}</p>
                        <p><span class="font-semibold">Weekly Budget:</span> KES {{ isset($profile->weekly_budget) ? number_format($profile->weekly_budget, 2) : '0.00' }}</p>
                    </div>
                    <p class="mt-4 text-xs text-emerald-700/90">Tip: update this profile anytime your health status or budget changes for better meal plan accuracy.</p>
                </aside>
                </div>
        </div>
    </div>
</x-app-layout>
