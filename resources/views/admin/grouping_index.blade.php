<x-layouts.app.admin>
    <x-slot name="breadcrumbs">
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Grouping System</span>
        </li>
    </x-slot>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Participant Grouping
                </h2>
                <p class="text-sm text-gray-600 mt-1">Organize participants into groups by event and category</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" 
               class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Page Intro --}}
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-8 rounded-2xl shadow-xl text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-white opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-3 bg-blue-400 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold">Grouping Module</h3>
                    </div>
                    <p class="text-blue-100 text-sm ml-1">
                        Select an event and category below to create groups, assign participants, and manage grouping efficiently.
                    </p>
                </div>
            </div>

            {{-- Events List --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Available Events</h3>
                </div>

                @forelse ($events as $event)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all border border-gray-100 overflow-hidden">
                        {{-- Event Header --}}
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-6 border-b border-gray-200">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h4>
                                    <div class="flex flex-wrap gap-3 text-sm">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>
                                            {{ $event->pesertas->count() }} Participants
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-100 text-purple-700 rounded-full font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ $event->groups->count() }} Groups
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Categories Grid --}}
                        <div class="p-6">
                            <p class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Select Category:
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                @php
                                    // Get unique categories for this event
                                    $categories = $event->pesertas->pluck('category')->unique()->sort()->values();
                                @endphp

                                @forelse ($categories as $category)
                                    @php
                                        $categoryCount = $event->pesertas->where('category', $category)->count();
                                        // Count groups that belong to this category
                                        // Since auto-grouping creates groups with category name prefix (e.g., "Adult Male Group 1")
                                        // we check if group name starts with the category name
                                        $groupsCount = $event->groups->filter(function($group) use ($category) {
                                            // Check if group name starts with category name
                                            return str_starts_with($group->name, $category);
                                        })->count();
                                    @endphp

                                    <a href="{{ route('admin.groups', ['event' => $event->id, 'category' => $category]) }}"
                                       class="group block p-5 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 transition-all hover:shadow-lg transform hover:-translate-y-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full group-hover:bg-blue-600"></div>
                                                    <p class="font-bold text-gray-800 group-hover:text-blue-600 transition text-lg">
                                                        {{ $category }}
                                                    </p>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <div class="flex items-center gap-2 text-xs text-gray-600">
                                                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        <span class="font-medium">{{ $categoryCount }}</span> participant{{ $categoryCount != 1 ? 's' : '' }}
                                                    </div>
                                                    <div class="flex items-center gap-2 text-xs text-gray-600">
                                                        <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                        </svg>
                                                        <span class="font-medium">{{ $groupsCount }}</span> group{{ $groupsCount != 1 ? 's' : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-full text-center py-8">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-gray-500 text-sm">No categories available for this event.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No events available for grouping.</p>
                        <p class="text-gray-400 text-sm mt-2">Create an event first to start organizing participants.</p>
                    </div>
                @endforelse

                {{-- Pagination Links --}}
                @if($events->hasPages())
                    <div class="flex justify-center mt-8">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app.admin>