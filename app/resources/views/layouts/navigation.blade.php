<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-emerald-100/70 bg-white/75 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @role('patient')
                        <x-nav-link :href="route('health-profiles.index')" :active="request()->routeIs('health-profiles.*')">
                            {{ __('Health Profile') }}
                        </x-nav-link>
                        <x-nav-link :href="route('meal-plans.index')" :active="request()->routeIs('meal-plans.*')">
                            {{ __('Meal Plan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('meal-logs.index')" :active="request()->routeIs('meal-logs.*')">
                            {{ __('Meal Logs') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endrole

                    @role('administrator')
                        <x-nav-link :href="route('foods.index')" :active="request()->routeIs('foods.*')">
                            {{ __('Foods & Prices') }}
                        </x-nav-link>
                        <x-nav-link :href="route('clinical-guidelines.index')" :active="request()->routeIs('clinical-guidelines.*')">
                            {{ __('Clinical Guidelines') }}
                        </x-nav-link>
                    @endrole

                    @role('nutritionist')
                        <x-nav-link :href="route('nutritionist.patients')" :active="request()->routeIs('nutritionist.*')">
                            {{ __('Patient Monitor') }}
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button type="button" data-theme-toggle class="me-3 rounded-xl border border-emerald-100 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-emerald-800 shadow-sm transition hover:border-emerald-200">
                    <span data-theme-label>Dark</span>
                </button>
                <span class="me-3 rounded-full border border-emerald-100 bg-emerald-50/70 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-800">
                    {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                </span>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-xl border border-emerald-100 bg-white px-3 py-2 text-sm leading-4 font-medium text-emerald-900 shadow-sm transition hover:border-emerald-200 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-600 hover:bg-red-50 focus:bg-red-50 focus:outline-none transition duration-150 ease-in-out">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @role('patient')
                <x-responsive-nav-link :href="route('health-profiles.index')" :active="request()->routeIs('health-profiles.*')">
                    {{ __('Health Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('meal-plans.index')" :active="request()->routeIs('meal-plans.*')">
                    {{ __('Meal Plan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('meal-logs.index')" :active="request()->routeIs('meal-logs.*')">
                    {{ __('Meal Logs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endrole

            @role('administrator')
                <x-responsive-nav-link :href="route('foods.index')" :active="request()->routeIs('foods.*')">
                    {{ __('Foods & Prices') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('clinical-guidelines.index')" :active="request()->routeIs('clinical-guidelines.*')">
                    {{ __('Clinical Guidelines') }}
                </x-responsive-nav-link>
            @endrole

            @role('nutritionist')
                <x-responsive-nav-link :href="route('nutritionist.patients')" :active="request()->routeIs('nutritionist.*')">
                    {{ __('Patient Monitor') }}
                </x-responsive-nav-link>
            @endrole
        </div>

        <div class="pt-4 pb-2 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <button type="button" data-theme-toggle class="mx-3 mb-1 w-[calc(100%-1.5rem)] rounded-xl border border-emerald-100 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-emerald-800 shadow-sm transition hover:border-emerald-200">
                    Toggle <span data-theme-label>Dark</span> Mode
                </button>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-red-600 hover:text-red-700 hover:bg-red-50 hover:border-red-200 focus:outline-none focus:text-red-700 focus:bg-red-50 focus:border-red-300 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
