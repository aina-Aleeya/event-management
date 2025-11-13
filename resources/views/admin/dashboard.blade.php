<x-layouts.app>
    {{-- Page header --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>

            {{-- Navigation Bar --}}
            <nav class="flex flex-wrap gap-2">
                <a href="" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                    Dashboard
                </a>
                <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Events
                </a>
                <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Participants
                </a>
                {{-- <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Reports
                </a> --}}
            </nav>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition duration-200">
                <p class="text-sm text-gray-500">Total Events</p>
                <p class="text-3xl font-bold">{{ count($events) }}</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition duration-200">
                <p class="text-sm text-gray-500">Total Participants</p>
                <p class="text-3xl font-bold">{{ $participantSummary->sum('total') }}</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition duration-200">
                <p class="text-sm text-gray-500">Most Viewed Event</p>
                <p class="text-xl font-semibold">
                    {{ $events->sortByDesc('click_count')->first()->title ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Event Click Statistics --}}
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Event Click Statistics</h2>

            <div class="overflow-x-auto">
                <table class="w-full shadow-sm rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <tr>
                            <th class="p-3 text-left">Event</th>
                            <th class="p-3 text-center">Click Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">{{ $event->title }}</td>
                                <td class="p-3 text-center font-semibold text-blue-700">{{ $event->click_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Participant Summary --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Participant Summary</h2>

            <div class="overflow-x-auto">
                <table class="w-full shadow-sm rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <tr>
                            <th class="p-3 text-left">Event</th>
                            <th class="p-3 text-left">Event Type</th>
                            <th class="p-3 text-center">Total Participants</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participantSummary as $item)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">
                                    <a href="{{ route('admin.event.dashboard', $item->event_id) }}"
                                        class="text-blue-600 hover:text-blue-800 underline">
                                        {{ $item->title ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="p-3">{{ $item->event_type }}</td>
                                <td class="p-3 text-center font-semibold text-green-700">{{ $item->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>