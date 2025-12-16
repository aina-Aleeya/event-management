<x-layouts.app.admin>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-6 py-10 space-y-8">

            {{-- Page Title --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Admin Dashboard
                    </h1>
                    <p class="text-gray-600 text-sm mt-2">Overview of events, participants, and engagement statistics</p>
                </div>
                <a href="{{ route('admin.create-event') }}"
                   class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Event
                </a>
            </div>

            {{-- Stats Overview --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Total Events --}}
                <div class="relative overflow-hidden p-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="p-4 bg-blue-400 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-blue-100 font-medium">Total Events</p>
                            <p class="mt-1 text-4xl font-extrabold text-white">
                                {{ count($events ?? []) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Total Participants --}}
                <div class="relative overflow-hidden p-6 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="p-4 bg-emerald-400 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100 font-medium">Total Participants</p>
                            <p class="mt-1 text-4xl font-extrabold text-white">
                                {{ $participantSummary->sum('total') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Most Viewed Event --}}
                <div class="relative overflow-hidden p-6 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
                    <div class="relative flex items-start gap-4">
                        <div class="p-4 bg-purple-400 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-purple-100 font-medium">Most Viewed Event</p>
                            <p class="mt-1 text-lg font-semibold text-white leading-tight">
                                {{ $events->sortByDesc('click_count')->first()->title ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Event Summary --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Event Summary
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Complete overview of all events and their metrics</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-sm">
                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 uppercase text-xs border-b-2 border-gray-200">
                            <tr>
                                <th class="p-4 text-left font-semibold">Event</th>
                                <th class="p-4 text-left font-semibold">Event Type</th>
                                <th class="p-4 text-center font-semibold">Total Participants</th>
                                <th class="p-4 text-center font-semibold">Click Count</th>
                                {{-- <th class="p-4 text-center font-semibold">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($participantSummary as $item)
                                <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                    <td class="p-4">
                                        <a href="{{ route('admin.event.dashboard', $item->event_id) }}"
                                           class="text-blue-600 hover:text-blue-800 font-semibold underline decoration-2 underline-offset-2 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                            {{ $item->title ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 bg-gradient-to-r from-slate-100 to-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                            {{ $item->event_type }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 rounded-full font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>
                                            {{ $item->total }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ $events->firstWhere('id', $item->event_id)->click_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        {{-- <a href="{{ route('admin.event.edit', $item->event_id) }}"
                                           class="text-sm text-blue-600 hover:text-blue-800 underline">
                                            Edit
                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app.admin>