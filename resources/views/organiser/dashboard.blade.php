<x-layouts.app>

    {{-- Organizer Dashboard Header --}}
    <div class="max-w-7xl mx-auto px-6 py-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Organizer Dashboard</h2>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Total Events -->
            <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
                <div class="flex justify-between items-start">
                    <div class="text-[10px] font-semibold text-gray-700">Total Events</div>
                    <div class="text-red-600">
                        <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                    </div>
                </div>
                <div class="text-xl font-bold text-red-700 leading-none">{{ count($events) }}</div>
                <div class="text-[10px] text-gray-500">&nbsp;</div>
            </div>

            <!-- Total Participants -->
            <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
                <div class="flex justify-between items-start">
                    <div class="text-[10px] font-semibold text-gray-700">Total Participants</div>
                    <div class="text-red-600">
                        <i class="fas fa-users text-gray-400 text-sm"></i>
                    </div>
                </div>
                <div class="text-xl font-bold text-red-700 leading-none">{{ $participantSummary->sum('total') }}</div>
                <div class="text-[10px] text-gray-500">&nbsp;</div>
            </div>

            <!-- Total Ticket Sold -->
            <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
                <div class="flex justify-between items-start">
                    <div class="text-[10px] font-semibold text-gray-700">Total Ticket Sold</div>
                    <div class="text-red-600">
                        <i class="fa-solid fa-ticket text-gray-400 text-sm"></i>
                    </div>
                </div>
                <div class="text-xl font-bold text-red-700 leading-none">{{ $totalTicketSold }}</div>
                <div class="text-[10px] text-gray-500">&nbsp;</div>
            </div>

            <!-- Total Sales -->
            <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
                <div class="flex justify-between items-start">
                    <div class="text-[10px] font-semibold text-gray-700">Total Sales</div>
                    <div class="text-red-600">
                        <i class="fas fa-money-bill-wave text-gray-400 text-sm"></i>
                    </div>
                </div>
                <div class="text-xl font-bold text-red-700 leading-none">RM {{ number_format($totalSales, 2) }}</div>
                <div class="text-[10px] text-gray-500">&nbsp;</div>
            </div>

        </div>
    </div>

    {{-- Event Click Statistics --}}
    <div class="max-w-7xl mx-auto px-6 py-4 space-y-8">
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Event Click Statistics</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="p-3 text-left">Event</th>
                            <th class="p-3 text-center">Click Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr class="border-t hover:bg-gray-50 transition-colors">
                                <td class="p-3">{{ $event->title }}</td>
                                <td class="p-3 text-center font-semibold text-blue-700">{{ $event->click_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Event Summary --}}
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Event Summary</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="p-3 text-left">Event</th>
                            <th class="p-3 text-left">Event Type</th>
                            <th class="p-3 text-center">Total Participants</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participantSummary as $item)
                            <tr class="border-t hover:bg-gray-50 transition-colors">
                                <td class="p-3">
                                    <a href="{{ route('organiser.event.dashboard', $item->event_id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium underline">
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
