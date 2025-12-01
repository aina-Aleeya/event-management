<x-layouts.app.admin>
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

        {{-- Page Title --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-500 text-sm">Overview of events, participants, and engagement statistics</p>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Total Events --}}
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition flex items-center gap-4">
                {{-- Icon --}}
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 7h18M3 12h18M3 17h18"/>
                    </svg>
                </div>

                {{-- Text --}}
                <div>
                    <p class="text-sm text-gray-500">Total Events</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-800">
                        {{ count($events ?? []) }}
                    </p>
                </div>
            </div>

            {{-- Total Participants --}}
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Total Participants</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-800">
                        {{ $participantSummary->sum('total') }}
                    </p>
                </div>
            </div>

            {{-- Most Viewed Event --}}
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition flex items-center gap-4">
                <div class="p-3 bg-purple-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Most Viewed Event</p>
                    <p class="mt-1 text-xl font-semibold text-purple-700">
                        {{ $events->sortByDesc('click_count')->first()->title ?? '—' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Event Click Statistics --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Event Click Statistics</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                        <tr>
                            <th class="p-3">Event</th>
                            <th class="p-3 text-center">Click Count</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @foreach ($events as $event)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="p-3">{{ $event->title }}</td>
                                <td class="p-3 text-center font-bold text-indigo-600">
                                    {{ $event->click_count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Participant Summary --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Participant Summary</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                        <tr>
                            <th class="p-3">Event</th>
                            <th class="p-3">Event Type</th>
                            <th class="p-3 text-center">Total Participants</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @foreach ($participantSummary as $item)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="p-3">
                                    <a href="{{ route('admin.participants', $item->event_id) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-semibold hover:underline">
                                        {{ $item->title ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="p-3">{{ $item->event_type }}</td>
                                <td class="p-3 text-center font-bold text-green-600">
                                    {{ $item->total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.app.admin>
