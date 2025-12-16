<header class="fixed top-0 left-0 z-50 w-full bg-white  border-b border-gray-200">
    
    <div class="flex w-full items-center justify-between px-6 py-3">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-3 hover:opacity-90 transition"
           wire:navigate>
            <div
                class="flex w-10 h-10 items-center justify-center rounded-full
                       bg-gradient-to-tr from-purple-500 via-pink-500 to-red-500
                       text-white shadow-lg">
                <x-app-logo-icon class="w-5 h-5 fill-current" />
            </div>
            <span class="font-bold text-lg tracking-wide">GreatEvent</span>
        </a>

        {{-- Right Menu --}}
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">
                Home
            </a>

            <a href="{{ route('events.page') }}"
               class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">
                Events
            </a>

            

            @auth
            <!-- Divider -->
            <div class="h-6 w-px bg-gray-700"></div>
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center gap-2 px-4 py-2 font-medium rounded-lg hover:bg-gray-100">
                    <span class="inline-flex h-8 w-8 items-center justify-center
                                rounded-full bg-red-300 text-white">
                        {{ auth()->user()->initials() }}
                    </span>
                    <span class="font-semibold">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-gray-500 text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="userMenuOpen" 
                        @click.outside="userMenuOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        x-cloak
                        class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg z-50">

                        {{-- Dropdown Items --}}
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
                            <i class="fa-solid fa-gear mr-2"></i> Settings
                        </a>

                        <a href="{{ route('history') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
                            <i class="fa-solid fa-ticket mr-2"></i> History Events
                        </a>

                        <div class="border-t my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Log out
                            </button>
                        </form>
                    </div>
                </div>
                @else
                    <!-- Before Login -->
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-black bg-red-200 px-3 py-1.5 rounded-lg hover:bg-red-300 transition">Login</a>
                    <a href="{{ route('register') }}"
                        class="text-sm font-medium text-white bg-red-400 px-3 py-1.5 rounded-lg hover:bg-red-500 transition">Register</a>
            @endauth

        </div>

    </div>
</header>
