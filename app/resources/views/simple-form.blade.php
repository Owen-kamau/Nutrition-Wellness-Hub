<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Form</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-emerald-50 p-6 sm:p-10">
    <div class="mx-auto w-full max-w-xl rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-bold text-emerald-900">Simple Form</h1>
        <p class="mt-1 text-sm text-emerald-700">Enter your details and submit.</p>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-100 px-4 py-3 text-sm text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('simple-form.submit') }}" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-emerald-900">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="100"
                    class="w-full rounded-lg border border-emerald-200 px-3 py-2 text-sm text-emerald-900 focus:border-emerald-500 focus:outline-none"
                >
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-emerald-900">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    maxlength="150"
                    class="w-full rounded-lg border border-emerald-200 px-3 py-2 text-sm text-emerald-900 focus:border-emerald-500 focus:outline-none"
                >
            </div>

            <div>
                <label for="message" class="mb-1 block text-sm font-medium text-emerald-900">Message</label>
                <textarea
                    id="message"
                    name="message"
                    required
                    maxlength="500"
                    rows="5"
                    class="w-full rounded-lg border border-emerald-200 px-3 py-2 text-sm text-emerald-900 focus:border-emerald-500 focus:outline-none"
                >{{ old('message') }}</textarea>
            </div>

            <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-800"
            >
                Submit
            </button>
        </form>
    </div>
</body>
</html>
