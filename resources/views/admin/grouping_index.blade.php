<x-layouts.app.admin>
    <x-slot name="breadcrumbs">
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Groups</span>
        </li>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-6">

        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Participant Grouping</h1>
                    <p class="text-sm text-gray-600 mt-1">Organize participants into groups by event and category</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Events -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">Total Events</p>
                        <p class="text-3xl font-bold text-blue-900">{{ $events->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Participants -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">Total Participants</p>
                        <p class="text-3xl font-bold text-green-900">{{ $totalParticipants ?? $events->sum(fn($e) => $e->pesertas->count()) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Groups -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 mb-1">Total Groups</p>
                        <p class="text-3xl font-bold text-purple-900">{{ $totalGroups ?? $events->sum(fn($e) => $e->groups->count()) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Search Events</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.grouping.index') }}" id="searchForm">
                    <div class="flex gap-3">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="searchInput" 
                                   value="{{ request('search') }}"
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" 
                                   placeholder="Search events by title..." />
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-blue-600 border border-blue-600 rounded-lg text-sm font-semibold text-white hover:bg-blue-700 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.grouping.index') }}"
                               class="inline-flex items-center px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
                @if(request('search'))
                    <p class="text-sm text-gray-600 mt-3">
                        Showing results for: <span class="font-semibold text-gray-900">"{{ request('search') }}"</span>
                    </p>
                @endif
            </div>
        </div>

        <!-- Events List -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Available Events</h3>
                        <p class="text-sm text-gray-600">Select an event to manage groups</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                    {{ $events->count() }} of {{ $events->total() }} events
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($events as $event)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all border border-gray-200 overflow-hidden">
                        
                        <!-- Event Header -->
                        <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $event->title }}</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold border border-blue-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            {{ $event->pesertas->count() }} Participants
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold border border-purple-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ $event->groups->count() }} Groups
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Grid -->
                        <div class="p-6">
                            <p class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Select Category:
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                @php
                                    $categories = $event->pesertas->pluck('category')->unique()->sort()->values();
                                @endphp

                                @forelse ($categories as $category)
                                    @php
                                        $categoryCount = $event->pesertas->where('category', $category)->count();
                                        $groupsCount = $event->groups->filter(function($group) use ($category) {
                                            return str_starts_with($group->name, $category);
                                        })->count();
                                    @endphp

                                    <a href="{{ route('admin.groups', ['event' => $event->id, 'category' => $category, 'from' => 'grouping-system']) }}"
                                       class="group block p-4 border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:shadow-lg transition-all">
                                        <div class="flex items-start justify-between gap-2 mb-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full group-hover:bg-blue-600 transition"></div>
                                                <p class="font-bold text-gray-800 group-hover:text-blue-600 transition text-sm">
                                                    {{ $category }}
                                                </p>
                                            </div>
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <div class="space-y-1.5">
                                            <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg border border-green-100">
                                                <span class="text-xs text-gray-600 font-medium">Participants</span>
                                                <span class="text-xs font-bold text-green-700">{{ $categoryCount }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-2 bg-purple-50 rounded-lg border border-purple-100">
                                                <span class="text-xs text-gray-600 font-medium">Groups</span>
                                                <span class="text-xs font-bold text-purple-700">{{ $groupsCount }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-full py-8 text-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-gray-500 text-sm font-medium">No categories available</p>
                                        <p class="text-gray-400 text-xs mt-1">Categories will appear once participants register</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-lg p-16 text-center border border-gray-200">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-20 h-20 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if(request('search'))
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                @endif
                            </svg>
                            @if(request('search'))
                                <h3 class="text-xl font-bold text-gray-800 mb-2">No Results Found</h3>
                                <p class="text-gray-500 mb-4">No events match your search for "{{ request('search') }}"</p>
                                <a href="{{ route('admin.grouping.index') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium border border-blue-200 hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear Search
                                </a>
                            @else
                                <h3 class="text-xl font-bold text-gray-800 mb-2">No Events Available</h3>
                                <p class="text-gray-500 mb-4">Create an event first to start organizing participants into groups.</p>
                                <a href="{{ route('admin.dashboard') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium border border-blue-200 hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Go to Dashboard
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $events->appends(['search' => request('search')])->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.app.admin>