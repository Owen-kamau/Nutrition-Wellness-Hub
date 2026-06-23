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
                <p class="mt-4 text-sm font-medium tracking-[0.18em] uppercase">Loading Secure Login</p>
            </div>
        </div>

        <div class="floating-bg one"></div>
        <div class="floating-bg two"></div>
        <div class="floating-bg three"></div>

        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
            <a href="/" class="mb-6 inline-flex items-center gap-2 rounded-2xl border border-white/70 bg-white/70 px-4 py-3 shadow-lg backdrop-blur">
                <x-application-logo class="h-10 w-10 fill-current text-emerald-800" />
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-emerald-700">Nutrition System</p>
                    <p class="text-sm font-semibold text-emerald-900">Secure Access</p>
                </div>
            </a>

            <div class="w-full max-w-md rounded-2xl border border-white/70 bg-white/78 p-6 shadow-2xl backdrop-blur-md sm:p-7">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
