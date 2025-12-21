<aside
    x-show="userMenuOpen"
    @click.outside="userMenuOpen = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:leave="transition ease-in duration-200"
    x-cloak>

    {{-- Sidebar Header --}}
    <div class="p-6 border-b flex items-center gap-4">
        <div
            class="w-16 h-16 rounded-full bg-red-400 flex items-center justify-center
                   text-white text-3xl font-bold">
            {{ auth()->user()->initials() }}
        </div>
        <div>
            <p class="text-sm text-gray-600">Welcome back,</p>
            <h2 class="text-lg font-bold">{{ auth()->user()->name }}</h2>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="py-2 space-y-1">
        <a href="{{ route('profile.edit') }}" @click="userMenuOpen = false"
           class="flex px-4 py-3 hover:bg-red-50">
            <i class="fa-solid fa-gear mr-2"></i> Settings
        </a>

        <a href="{{ route('history') }}" @click="userMenuOpen = false"
           class="flex px-4 py-3 hover:bg-red-50">
            <i class="fa-solid fa-ticket mr-2"></i> My Ticket History
        </a>

        <a href="{{ route('create-event') }}" @click="userMenuOpen = false"
           class="flex px-4 py-3 hover:bg-red-50">
            <i class="fa-solid fa-calendar mr-2"></i> Create Event
        </a>

        <div class="border-t my-2"></div>

        <a href="{{ route('organiser.dashboard') }}" @click="userMenuOpen = false"
           class="flex px-4 py-3 hover:bg-red-50">
            <i class="fa-solid fa-laptop-file mr-2"></i> Organizer Dashboard
        </a>
    </nav>

    {{-- Logout --}}
    <div class="border-t p-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex w-full px-4 py-3 text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-right-from-bracket mr-2"></i> Log out
            </button>
        </form>
    </div>

</aside>
