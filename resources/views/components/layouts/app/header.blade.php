<header class="fixed top-0 left-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
    
    <div class="flex w-full items-center justify-between px-6 lg:px-12 py-4">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-3 hover:opacity-90 transition-all group"
           wire:navigate>
            <div class="flex w-12 h-12 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 text-white shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all">
                <x-app-logo-icon class="w-6 h-6 fill-current" />
            </div>
            <span class="font-bold text-xl tracking-wide bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                GreatEvent
            </span>
        </a>

        {{-- Navigation Menu --}}
        <nav class="hidden md:flex items-center space-x-2">
            <a href="{{ route('dashboard') }}"
               class="px-5 py-2.5 font-semibold rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all {{ request()->routeIs('dashboard') ? 'bg-purple-50 text-purple-600' : 'text-gray-700' }}">
                <i class="fa-solid fa-home mr-2"></i>Home
            </a>

            <a href="{{ route('events.page') }}"
               class="px-5 py-2.5 font-semibold rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all {{ request()->routeIs('events.page') ? 'bg-purple-50 text-purple-600' : 'text-gray-700' }}">
                <i class="fa-solid fa-calendar-days mr-2"></i>Events
            </a>
        </nav>

        {{-- Right Menu --}}
        <div class="flex items-center space-x-4">
            @auth
                <!-- User Dropdown -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-3 px-4 py-2.5 font-medium rounded-xl hover:bg-gray-50 transition-all border border-gray-200 hover:border-purple-300 hover:shadow-md">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 text-white font-bold shadow-md">
                            {{ auth()->user()->initials() }}
                        </span>
                        <span class="hidden lg:block font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-gray-500 text-xs transition-transform" :class="{ 'rotate-180': userMenuOpen }"></i>
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
                        class="absolute right-0 mt-3 w-64 bg-white border border-gray-200 rounded-2xl shadow-2xl z-50 overflow-hidden">

                        <!-- User Info Header -->
                        <div class="px-5 py-4 bg-gradient-to-br from-purple-50 to-pink-50 border-b border-gray-200">
                            <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                <i class="fa-solid fa-gear w-5"></i>
                                <span class="font-medium">Settings</span>
                            </a>

                            <a href="{{ route('history') }}" 
                               class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                <i class="fa-solid fa-clock-rotate-left w-5"></i>
                                <span class="font-medium">Event History</span>
                            </a>
                        </div>

                        <div class="border-t border-gray-200"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="py-2">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center gap-3 w-full px-5 py-3 text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fa-solid fa-right-from-bracket w-5"></i>
                                <span class="font-medium">Log out</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Before Login -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="px-6 py-2.5 font-semibold text-purple-600 bg-purple-50 rounded-xl hover:bg-purple-100 transition-all border border-purple-200 hover:border-purple-300">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-6 py-2.5 font-semibold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        Register
                    </a>
                </div>
            @endauth

            <!-- Mobile Menu Button -->
            <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" x-data @click="$dispatch('toggle-mobile-menu')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-data="{ mobileMenuOpen: false }" 
         @toggle-mobile-menu.window="mobileMenuOpen = !mobileMenuOpen"
         x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak
         class="md:hidden border-t border-gray-200 bg-white">
        <nav class="px-6 py-4 space-y-2">
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-3 font-semibold rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all {{ request()->routeIs('dashboard') ? 'bg-purple-50 text-purple-600' : 'text-gray-700' }}">
                <i class="fa-solid fa-home mr-2"></i>Home
            </a>
            <a href="{{ route('events.page') }}"
               class="block px-4 py-3 font-semibold rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all {{ request()->routeIs('events.page') ? 'bg-purple-50 text-purple-600' : 'text-gray-700' }}">
                <i class="fa-solid fa-calendar-days mr-2"></i>Events
            </a>
        </nav>
    </div>

    <!-- Breadcrumbs Section -->
    @php
        use App\Helpers\BreadcrumbHelper;
        $autoBreadcrumbs = BreadcrumbHelper::generate();
        $showBreadcrumbs = isset($breadcrumbs) || View::hasSection('breadcrumbs') || !empty($autoBreadcrumbs);
    @endphp
    
    @if($showBreadcrumbs)
    <nav class="fixed left-0 z-40 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
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

</header>