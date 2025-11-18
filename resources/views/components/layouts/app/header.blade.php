<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    @include('partials.head')
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">


    <div x-data="{ sidebarOpen: false }" class="relative min-h-screen">

        <!-- Header Section -->
        <header class="fixed top-0 left-0 z-50 w-full bg-white shadow-md border-b border-gray-200">
            <div class="flex w-full items-center justify-between px-6 py-3">

        <!-- Left: Logo + System Name -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 hover:opacity-90 transition" wire:navigate>
            <!-- Logo Circle with Gradient for Energy -->
            <div class="flex aspect-square w-10 h-10 items-center justify-center rounded-full bg-gradient-to-tr from-purple-500 via-pink-500 to-white-500 text-white shadow-lg">
                <x-app-logo-icon class="w-5 h-5 fill-current" />
            </div>
            <!-- System Name -->
            <span class="font-sans font-bold text-black text-lg md:text-xl tracking-wide">
                GreatEvent
            </span>
        </a>

                <!-- Right: Menu / Auth -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 text-gray-900 font-medium rounded-lg hover:bg-gray-100 transition">Home</a>
                    <a href="{{ route('events.page') }}"
                        class="px-4 py-2 text-gray-900 font-medium rounded-lg hover:bg-gray-100 transition">Events</a>
                    <a href="{{ route('history') }}"
                        class="px-4 py-2 text-gray-900 font-medium rounded-lg hover:bg-gray-100 transition">History</a>

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
                                                    class="flex h-full w-full items-center justify-center rounded-lg bg-red-300 text-white dark:bg-red-400">
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
                            class="text-sm font-medium text-black bg-red-200 px-3 py-1.5 rounded-lg hover:bg-red-400 transition">Login</a>
                        <a href="{{ route('register') }}"
                            class="text-sm font-medium text-white bg-red-400 px-3 py-1.5 rounded-lg hover:bg-red-500 transition">Register</a>
                    @endauth
                </div>

            </div>
        </header>

        <!-- Sidebar -->
        @auth
            <aside x-show="sidebarOpen" @click.outside="sidebarOpen = false" x-transition
                class="fixed top-0 right-0 h-full w-80 bg-white shadow-lg z-50 transform transition-transform duration-300 ease-in-out">

                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-200 flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-full bg-red-400 flex items-center justify-center text-white text-3xl font-bold">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Welcome back,</p>
                        <h2 class="text-lg font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="flex-grow py-2 px-2 space-y-1">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 rounded-md">
                        <i class="icon-fa-gear mr-2"></i>Settings
                    </a>
                    <a href="{{ route('history') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 rounded-md">
                        <i class="icon-fa-ticket mr-2"></i>My Ticket History
                    </a>
                    <a href="{{ route('create-event') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 rounded-md">
                        <i class="icon-fa-calendar mr-2"></i>Create Event
                    </a>

                    <div class="border-t border-black my-1"></div>

                    <a href="{{ route('organiser.dashboard') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 rounded-md">
                        <i class="icon-fa-dashboard mr-2"></i>Organizer Dashboard
                    </a>
                </nav>

                <!-- Sidebar Logout -->
                <div class="p-2 border-t border-black">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 rounded-md">
                            <i class="icon-fa-sign-out mr-2"></i>Log out
                        </button>
                    </form>
                </div>
            </aside>
        @endauth

        <!-- Page Content -->
        <main class="pt-9">
            {{ $slot }}
        </main>

    </div>

    @livewireScripts
    @fluxScripts
    @stack('scripts')
</body>

</html>
