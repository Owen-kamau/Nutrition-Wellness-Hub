<x-app-layout>
    <x-slot name="header">
        <div class="glass-card p-6">
            <h2 class="section-title">Nutritionist Patient Monitor</h2>
            <p class="section-subtitle">Review profiles, risk conditions, and meal-log activity.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell">
            <div class="glass-card p-6">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Age</th>
                                <th>Condition</th>
                                <th>BMI</th>
                                <th>Budget (KES)</th>
                                <th>Meal Logs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                                <tr>
                                    <td>{{ $patient->name }}</td>
                                    <td>{{ $patient->healthProfile->age ?? '-' }}</td>
                                    <td>{{ ucfirst($patient->healthProfile->medical_condition ?? 'none') }}</td>
                                    <td>{{ $patient->healthProfile->bmi ?? '-' }}</td>
                                    <td>{{ $patient->healthProfile ? number_format($patient->healthProfile->weekly_budget, 2) : '-' }}</td>
                                    <td>{{ $patient->meal_logs_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state my-3">
                                            <div class="empty-state-icon">N</div>
                                            <p class="font-semibold text-emerald-900">No patients assigned</p>
                                            <p class="mt-1 text-sm text-emerald-800/85">Patient profiles will appear here for monitoring once registrations are active.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $patients->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
