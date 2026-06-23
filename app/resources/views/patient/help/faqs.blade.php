<x-app-layout>
    <x-slot name="header">
        <div class="glass-card overflow-hidden p-0">
            <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-700 px-6 py-6 text-white sm:px-8">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-100">Patient Support</p>
                <h2 class="mt-2 text-2xl font-bold sm:text-3xl">FAQs</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="section-shell">
            <section class="glass-card p-6">
                <h3 class="text-lg font-bold text-emerald-950">Frequently Asked Questions</h3>
                <div class="mt-2 space-y-2 text-sm text-emerald-900">
                    <p><span class="font-semibold">Can I update my condition and budget?</span> Yes. Update your Health Profile at any time.</p>
                    <p><span class="font-semibold">Do I need to log every meal?</span> Yes. Daily logs improve your adherence tracking.</p>
                    <p><span class="font-semibold">Can I export my records?</span> Yes. Use Reports to export PDF or Excel.</p>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
