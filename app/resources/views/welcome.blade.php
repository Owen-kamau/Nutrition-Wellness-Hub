<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kenya Nutrition System</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="page-loader" class="page-loader">
        <div class="text-center text-white">
            <div class="loader-orb mx-auto"></div>
            <p class="mt-4 text-sm font-medium tracking-[0.18em] uppercase">Preparing Your Nutrition Workspace</p>
        </div>
    </div>

    <div class="floating-bg one"></div>
    <div class="floating-bg two"></div>

    <div class="mx-auto max-w-6xl px-5 py-10 sm:py-16">
        <header class="glass-card p-6 sm:p-8">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700">Kenya Data-Informed Nutrition Platform</p>
            <h1 class="mt-3 text-4xl font-extrabold leading-tight text-emerald-950 sm:text-6xl">Precision Meal Planning For Patients, Nutritionists, And Admin Teams</h1>
            <p class="mt-4 max-w-3xl text-sm text-emerald-900/80 sm:text-base">Generate condition-aware, budget-sensitive 7-day meal plans using local foods, then track adherence, calories, and reporting in one secure system.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="brand-btn">Open Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="brand-btn">Login</a>
                    <a href="{{ route('register') }}" class="brand-btn-secondary">Register As Patient</a>
                @endauth
            </div>
        </header>

        <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <article class="glass-card p-5">
                <h2 class="font-bold text-emerald-900">Health Profile</h2>
                <p class="mt-2 text-sm text-emerald-800">Age, BMI, condition, and budget inputs drive planning logic.</p>
            </article>
            <article class="glass-card p-5">
                <h2 class="font-bold text-emerald-900">Meal Engine</h2>
                <p class="mt-2 text-sm text-emerald-800">Applies diabetes and hypertension rules with food affordability checks.</p>
            </article>
            <article class="glass-card p-5">
                <h2 class="font-bold text-emerald-900">Food Logging</h2>
                <p class="mt-2 text-sm text-emerald-800">Daily and weekly adherence tracking with calorie and budget visibility.</p>
            </article>
            <article class="glass-card p-5">
                <h2 class="font-bold text-emerald-900">Reports</h2>
                <p class="mt-2 text-sm text-emerald-800">Export practical PDF and Excel nutrition reports for clinical workflows.</p>
            </article>
        </section>
    </div>
</body>
</html>
