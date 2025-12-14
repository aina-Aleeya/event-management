<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    @include('partials.head')
    @livewireStyles
</head>

<body class="min-h-screen bg-white text-black">

    <!-- Header Section -->
    <header
        class="fixed top-0 left-0 z-50 w-full bg-purple-200/70 backdrop-blur-md border-b border-purple-300/50 shadow-md transition-all duration-300">
        <div class="flex w-full items-center justify-between px-6 py-2 text-gray-900">

            <!-- Left: Logo + System Name -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 hover:opacity-80 transition"
                    wire:navigate>
                    <x-app-logo class="size-8 text-purple-400" />
                </a>
            </div>

            <!-- Right: Home, Event, User Menu -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}"
                class="px-3 py-1.5 rounded-lg hover:bg-blue-200 transition">Home</a>
                <a href="{{ route('admin.event-approval') }}"
                class="px-3 py-1.5 rounded-lg hover:bg-blue-200 transition">Event Approval</a>
                <a href="{{ route('admin.grouping.index') }}"
                class="px-3 py-1.5 rounded-lg hover:bg-blue-200 transition">Grouping System</a>


                <!-- Right: Auth / User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- User Dropdown -->
                        <flux:dropdown position="top" align="end">
                            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

                            <flux:menu class="bg-white dark:bg-gray-800">
                                <flux:menu.radio.group>
                                    <div class="p-0 text-sm font-normal">
                                        <div class="flex items-center gap-2 px-2 py-2 text-start text-sm">
                                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                                <span
                                                    class="flex h-full w-full items-center justify-center rounded-lg bg-purple-300 text-white dark:bg-purple-400">
                                                    {{ auth()->user()->initials() }}
                                                </span>
                                            </span>

                                            <div class="grid flex-1 text-start text-sm leading-tight">
                                                <span
                                                    class="truncate font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                                                <span
                                                    class="truncate text-xs text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </flux:menu.radio.group>

                                <flux:menu.separator />

                                <flux:menu.radio.group>
                                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                        {{ __('Settings') }}
                                    </flux:menu.item>
                                </flux:menu.radio.group>

                                <flux:menu.separator />

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
                            class="text-sm font-medium text-black bg-purple-400 px-3 py-1.5 rounded-lg hover:bg-purple-500 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="text-sm font-medium text-black bg-purple-400 px-3 py-1.5 rounded-lg hover:bg-purple-500 transition">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
    </header>

    <!-- Breadcrumbs Section -->
    @if(isset($breadcrumbs) || View::hasSection('breadcrumbs'))
    <nav class="fixed top-14 left-0 z-40 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </li>

                @isset($breadcrumbs)
                    {{ $breadcrumbs }}
                @else
                    @yield('breadcrumbs')
                @endisset
            </ol>
        </div>
    </nav>
    @endif

    <!-- Page Content -->
    <main class="pt-28 p-4">
        {{ $slot }}
    </main>

    @livewireScripts
    @fluxScripts
    @stack('scripts')
</body>

</html>