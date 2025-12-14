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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Participant Grouping
            </h2>
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                Dashboard
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

        {{-- Page Intro --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-2">Grouping Module</h3>
            <p class="text-gray-600">
                Select an event and category below to create groups, assign participants, and manage categories.
            </p>
        </div>

        {{-- Events List --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-4">Available Events</h3>

            @forelse ($events as $event)
                <div class="border rounded-lg p-6 mb-6">
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold mb-1">{{ $event->title }}</h4>
                        <p class="text-gray-600 text-sm">
                            {{ $event->pesertas->count() }} total participants · 
                            {{ $event->groups->count() }} groups created
                        </p>
                    </div>

                    {{-- Categories Grid --}}
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Select Category:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            @php
                                // Get unique categories for this event
                                $categories = $event->pesertas->pluck('category')->unique()->sort()->values();
                            @endphp

                            @forelse ($categories as $category)
                                @php
                                    $categoryCount = $event->pesertas->where('category', $category)->count();
                                    $groupsCount = $event->groups->filter(function($group) use ($category) {
                                        return $group->pesertas->where('category', $category)->count() > 0;
                                    })->count();
                                @endphp

                                <a href="{{ route('admin.groups', ['event' => $event->id, 'category' => $category]) }}"
                                   class="block p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-800 group-hover:text-blue-600 transition">
                                                {{ $category }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $categoryCount }} participant{{ $categoryCount != 1 ? 's' : '' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $groupsCount }} group{{ $groupsCount != 1 ? 's' : '' }}
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full text-gray-500 text-sm py-2">
                                    No categories available for this event.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No events available for grouping.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app.admin>