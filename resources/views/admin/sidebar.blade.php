<aside class="w-64 bg-gray-800 text-white flex flex-col min-h-screen">
    <div class="p-4 border-b border-gray-700">
        <h1 class="text-xl font-bold">Admin Panel</h1>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-700 
                  @if(request()->routeIs('admin.dashboard')) bg-gray-700 @endif">
            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.participants', 1) }}"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-700 
                  @if(request()->routeIs('admin.participants')) bg-gray-700 @endif">
            <i class="fa-solid fa-users"></i>
            Participants
        </a>

        <a href="{{ route('admin.groups', 1) }}"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-700 
                  @if(request()->routeIs('admin.groups')) bg-gray-700 @endif">
            <i class="fa-solid fa-people-group"></i>
            Groups
        </a>
    </nav>

    <div class="p-4 border-t border-gray-700 text-sm text-gray-400">
        © {{ date('Y') }} Your Company
    </div>
</aside>
