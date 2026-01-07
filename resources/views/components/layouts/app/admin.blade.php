<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    @include('partials.head')
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">

    <!-- Header Navigation -->
    <header class="fixed top-0 left-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="relative flex items-center justify-between h-16">

                <!-- Left: Logo + System Name -->
                <div class="flex items-center gap-8">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('organiser.dashboard') }}" 
                       class="flex items-center gap-3 hover:opacity-80 transition group"
                       wire:navigate>
                        <x-app-logo class="w-8 h-8 text-purple-600 group-hover:text-purple-700 transition" />
                    </a>
                </div>

                <!-- Center: Navigation Links (Absolutely Positioned) -->
                <nav class="hidden md:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('organiser.dashboard') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') || request()->routeIs('organiser.dashboard') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </div>
                    </a>

                    {{-- Grouping - Available for both Admin and Organiser --}}
                    <a href="{{ route('admin.grouping.index') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.grouping.*') || request()->routeIs('admin.groups') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Groups
                        </div>
                    </a>

                    {{-- Organisers - Admin Only --}}
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.organisers.index') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.organisers.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Organisers
                            </div>
                        </a>
                    @endif
                </nav>

                <!-- Right: User Menu -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- User Dropdown -->
                        <flux:dropdown position="top" align="end">
                            <flux:button variant="ghost" size="sm" class="!p-0">
                                <div class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg transition">
                                    <div class="hidden sm:block text-right">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                        {{ auth()->user()->initials() }}
                                    </div>
                                </div>
                            </flux:button>

                            <flux:menu class="bg-white dark:bg-gray-800 min-w-[240px]">
                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ auth()->user()->initials() }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ auth()->user()->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ auth()->user()->email }}
                                            </p>
                                            <p class="text-xs text-purple-600 dark:text-purple-400 font-medium mt-0.5">
                                                {{ ucfirst(auth()->user()->role) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <flux:menu.radio.group>
                                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                        {{ __('Settings') }}
                                    </flux:menu.item>
                                </flux:menu.radio.group>

                                <flux:menu.separator />

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                        class="w-full text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                                        {{ __('Log Out') }}
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    @else
                        <!-- Before Login -->
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition shadow-sm">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-purple-600 text-sm font-medium rounded-lg border border-purple-600 hover:bg-purple-50 transition">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <div class="md:hidden fixed top-16 left-0 right-0 z-40 bg-white border-b border-gray-200 shadow-sm">
        <nav class="flex items-center gap-1 px-6 py-2 overflow-x-auto">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('organiser.dashboard') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request()->routeIs('admin.dashboard') || request()->routeIs('organiser.dashboard') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.grouping.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request()->routeIs('admin.grouping.*') || request()->routeIs('admin.groups') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100' }}">
                Groups
            </a>

            {{-- @if(auth()->user()->isOrganiser())
                <a href="{{ route('admin.events.team.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request()->routeIs('organiser.events.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    Manage Team
                </a>
            @endif --}}

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.organisers.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request()->routeIs('admin.organisers.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    Organisers
                </a>
            @endif
        </nav>
    </div>

    <!-- Breadcrumbs Section -->
    @php
        use App\Helpers\BreadcrumbHelper;
        $autoBreadcrumbs = BreadcrumbHelper::generate();
        $showBreadcrumbs = isset($breadcrumbs) || View::hasSection('breadcrumbs') || !empty($autoBreadcrumbs);
    @endphp
    
    @if($showBreadcrumbs)
    <nav class="fixed top-16 md:top-16 left-0 z-40 w-full bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('organiser.dashboard') }}" 
                       class="flex items-center gap-1 hover:text-purple-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Manual breadcrumbs from slot --}}
                @isset($breadcrumbs)
                    {{ $breadcrumbs }}
                @else
                    {{-- Try section --}}
                    @if(View::hasSection('breadcrumbs'))
                        @yield('breadcrumbs')
                    @else
                        {{-- Auto-generated breadcrumbs --}}
                        {!! BreadcrumbHelper::render() !!}
                    @endif
                @endisset
            </ol>
        </div>
    </nav>
    @endif

    <!-- Page Content -->
    <main class="pt-16 {{ $showBreadcrumbs ? 'md:pt-[112px]' : '' }}">
        {{ $slot }}
    </main>

    @livewireScripts
    @fluxScripts
    @stack('scripts')
</body>

</html>