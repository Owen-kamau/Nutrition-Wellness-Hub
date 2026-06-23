<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nutrition System') }}</title>
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div id="page-loader" class="page-loader">
            <div class="text-center text-white">
                <div class="loader-orb mx-auto"></div>
                <p class="mt-4 text-sm font-medium tracking-[0.18em] uppercase">Loading Nutrition Intelligence</p>
            </div>
        </div>

        <div class="floating-bg one"></div>
        <div class="floating-bg two"></div>
        <div class="floating-bg three"></div>

        <div class="min-h-screen pb-12">
            @include('layouts.navigation')

            @isset($header)
                <header class="pt-6 sm:pt-8">
                    <div class="section-shell py-4 sm:py-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="fade-in">
                {{ $slot }}
            </main>

            <footer class="section-shell mt-10 pb-6">
                @auth
                    @if(auth()->user()->hasRole('patient'))
                        <nav class="mb-3 flex flex-wrap items-center justify-center gap-2" aria-label="Footer help links">
                            <a href="{{ route('patient.help.about') }}" class="rounded-full bg-emerald-900 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-white transition hover:bg-emerald-800">About</a>
                            <a href="{{ route('patient.help.how-to-use') }}" class="rounded-full bg-emerald-900 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-white transition hover:bg-emerald-800">How To Use</a>
                            <a href="{{ route('patient.help.faqs') }}" class="rounded-full bg-emerald-900 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-white transition hover:bg-emerald-800">FAQs</a>
                            <a href="{{ route('patient.help.contact') }}" class="rounded-full bg-emerald-900 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-white transition hover:bg-emerald-800">Contact</a>
                        </nav>
                    @endif
                @endauth
                <p class="text-center text-xs text-emerald-800/85">&copy; {{ now()->year }} Nutrition System. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html>
