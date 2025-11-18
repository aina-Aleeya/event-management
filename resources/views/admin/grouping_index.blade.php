<x-layouts.app.admin>
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
                Select an event below to create groups, assign participants, and manage categories.
            </p>
        </div>

        {{-- Events List --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-4">Available Events</h3>

            @forelse ($events as $event)
                <div class="border rounded-lg p-4 mb-4 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold">{{ $event->title }}</h4>
                        <p class="text-gray-600 text-sm">
                            {{ $event->pesertas->count() }} participants · 
                            {{ $event->groups->count() }} groups created
                        </p>
                    </div>

                    <a href="{{ route('admin.groups', $event->id) }}"
                       class="mt-3 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Manage Groups
                    </a>
                </div>
            @empty
                <p class="text-gray-500">No events available for grouping.</p>
            @endforelse
        </div>

    </div>
</x-layouts.app.admin>
