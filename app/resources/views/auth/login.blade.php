<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-4 rounded-xl border border-emerald-100 bg-white/80 p-3 text-xs text-emerald-800">
        Switching account?
        <button
            type="button"
            data-clear-login
            class="ms-1 font-semibold text-emerald-900 underline decoration-emerald-300 underline-offset-2"
        >
            Use another account
        </button>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-4" x-data="{ remember: @js((bool) old('remember')) }">
            <label for="remember_me"
                   class="block cursor-pointer rounded-2xl border border-emerald-100 bg-white/90 p-4 shadow-sm transition duration-200 hover:border-emerald-300"
                   :class="remember ? 'border-emerald-400 ring-4 ring-emerald-100/70 shadow-md' : ''">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-emerald-900">Remember this device</p>
                        <p class="mt-1 text-xs text-emerald-700/90">Use this only on your personal phone or computer.</p>
                    </div>

                    <span class="rounded-full px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.08em]"
                          :class="remember ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                          x-text="remember ? 'Enabled' : 'Off'"></span>
                </div>

                <div class="mt-3 flex items-center gap-3">
                    <input id="remember_me"
                           type="checkbox"
                           class="h-4 w-4 rounded border-gray-300 text-emerald-700 shadow-sm focus:ring-emerald-500"
                           name="remember"
                           x-model="remember">

                    <p class="text-xs text-emerald-700/90" x-show="!remember" x-transition.opacity.duration.150ms>
                        Extra safe: you'll be asked to log in each time.
                    </p>
                    <p class="text-xs text-emerald-700/90" x-show="remember" x-transition.opacity.duration.150ms>
                        Faster return: stay signed in on this browser.
                    </p>
                </div>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
