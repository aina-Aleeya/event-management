<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">

    <!-- Sidebar (desktop & mobile) -->
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-teal-50 dark:bg-zinc-900 dark:border-zinc-700 text-gray-900 dark:text-gray-100">
        <!-- Mobile close toggle -->
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse px-4 py-3 hover:bg-teal-100 dark:hover:bg-zinc-800" wire:navigate>
            <x-app-logo class="text-teal-600" />
            <span class="font-semibold text-teal-700 dark:text-teal-300">My System</span>
        </a>

        <!-- Navigation items -->
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item 
                    icon="home" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('dashboard')" 
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- User menu in sidebar (desktop) -->
        @auth
            <flux:dropdown class="hidden lg:block px-4 pb-4" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="bg-white dark:bg-zinc-900 rounded-lg shadow-md overflow-hidden text-gray-900 dark:text-gray-100">
                    <flux:menu.radio.group>
                        <div class="p-2 text-sm font-normal border-b border-gray-200 dark:border-zinc-800">
                            <div class="flex items-center gap-2 px-2 py-2">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-500 text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                                <div>
                                    <span class="block font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
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

                    <form method="POST" action="{{ route('logout') }}" class="w-full border-t border-gray-200 dark:border-zinc-800">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @else
            <!-- Guest: login / register links in sidebar -->
            <div class="hidden lg:flex flex-col gap-3 mt-4 px-4 pb-6">
                <a href="{{ route('login') }}" class="text-sm font-medium text-teal-700 hover:text-teal-900 dark:text-teal-300 transition">Login</a>
                <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-teal-600 px-3 py-1.5 rounded-lg hover:bg-teal-700 transition">Register</a>
            </div>
        @endauth

    </flux:sidebar>

    <!-- Mobile header for sidebar toggling and user -->
    <flux:header class="lg:hidden bg-teal-600 text-white">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />

        @auth
            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

                <flux:menu class="bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100">
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>Settings</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Log Out</flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @else
            <div class="flex items-center space-x-4 me-4 px-4 py-3">
                <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:opacity-90 transition">Login</a>
                <a href="{{ route('register') }}" class="text-sm font-medium bg-white text-teal-600 px-3 py-1.5 rounded-lg hover:bg-teal-100 transition">Register</a>
            </div>
        @endauth
    </flux:header>

    <!-- Main content -->
    {{ $slot }}

    @fluxScripts
</body>
</html>

