<x-app-layout>
    <x-slot name="header">
        <div class="glass-card overflow-hidden p-0">
            <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-700 px-6 py-6 text-white sm:px-8">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-100">Patient Support</p>
                <h2 class="mt-2 text-2xl font-bold sm:text-3xl">How To Use</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell">
            <section class="glass-card p-6">
                <h3 class="text-lg font-bold text-emerald-950">How To Use The System</h3>
                <ol class="mt-2 list-decimal space-y-1 pl-5 text-sm text-emerald-900">
                    <li>Complete or update your Health Profile.</li>
                    <li>Generate your Meal Plan.</li>
                    <li>Log meals daily in Meal Logs.</li>
                    <li>Review your progress through Reports.</li>
                </ol>
            </section>
        </div>
    </div>
</x-app-layout>
