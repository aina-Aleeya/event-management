<div class="min-h-screen bg-gradient-to-b from-purple-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Header Section --}}
        <div class="text-center mb-10 mt-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl mb-4 shadow-lg">
                <i class="fa-solid fa-clock-rotate-left text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                Event History
            </h1>
            <p class="text-gray-600 text-lg">
                View all your registered events and payment status
            </p>
        </div>

        {{-- Events Table Card --}}
        <div class="bg-white shadow-2xl rounded-3xl border border-gray-200 overflow-hidden">
            
            @if(count($historyEvent) > 0)
                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-calendar-days mr-2"></i>
                                    Event
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-users mr-2"></i>
                                    Participants
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-credit-card mr-2"></i>
                                    Payment Status
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($historyEvent as $index => $hs)
                                <tr class="hover:bg-purple-50 transition-colors duration-200 cursor-pointer group"
                                    onclick="window.location='{{ route('history.participant', ['eventId' => $hs->event_id]) }}'">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                                    {{ $hs->title ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-full font-bold">
                                            <i class="fa-solid fa-user"></i>
                                            {{ $hs->total ?? '0' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if ($hs->pending_count > 0 && $hs->payment_status === 'Pending')
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full font-bold">
                                                <i class="fa-solid fa-clock"></i>
                                                {{ $hs->pending_count }} Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-full font-bold">
                                                <i class="fa-solid fa-circle-check"></i>
                                                All Paid
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <button class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-1 transition-all">
                                            View Details
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($historyEvent as $index => $hs)
                        <div class="p-6 hover:bg-purple-50 transition-colors cursor-pointer"
                             onclick="window.location='{{ route('history.participant', ['eventId' => $hs->event_id]) }}'">
                            
                            {{-- Event Title --}}
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md flex-shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 text-lg mb-1">
                                        {{ $hs->title ?? '-' }}
                                    </h3>
                                </div>
                            </div>

                            {{-- Stats Grid --}}
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                {{-- Participants --}}
                                <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-100">
                                    <p class="text-xs text-blue-600 font-semibold uppercase mb-1">Participants</p>
                                    <p class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                                        <i class="fa-solid fa-users"></i>
                                        {{ $hs->total ?? '0' }}
                                    </p>
                                </div>

                                {{-- Payment Status --}}
                                <div class="bg-{{ $hs->pending_count > 0 && $hs->payment_status === 'Pending' ? 'yellow' : 'green' }}-50 rounded-xl p-4 border-2 border-{{ $hs->pending_count > 0 && $hs->payment_status === 'Pending' ? 'yellow' : 'green' }}-100">
                                    <p class="text-xs text-{{ $hs->pending_count > 0 && $hs->payment_status === 'Pending' ? 'yellow' : 'green' }}-600 font-semibold uppercase mb-1">Payment</p>
                                    @if ($hs->pending_count > 0 && $hs->payment_status === 'Pending')
                                        <p class="text-lg font-bold text-yellow-700 flex items-center gap-1">
                                            <i class="fa-solid fa-clock"></i>
                                            <span class="text-sm">{{ $hs->pending_count }} Pending</span>
                                        </p>
                                    @else
                                        <p class="text-lg font-bold text-green-700 flex items-center gap-2">
                                            <i class="fa-solid fa-circle-check"></i>
                                            All Paid
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- View Button --}}
                            <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-bold hover:shadow-lg transition-all">
                                View Details
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

            @else
                {{-- Empty State --}}
                <div class="text-center py-20 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                        <i class="fa-solid fa-inbox text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">No Event History</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        You haven't registered for any events yet. Start exploring events and join the community!
                    </p>
                    <a href="{{ route('events.page') }}" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-calendar-days"></i>
                        Browse Events
                    </a>
                </div>
            @endif

        </div>

        {{-- Info Card --}}
        @if(count($historyEvent) > 0)
            <div class="mt-8 bg-gradient-to-r from-purple-100 to-pink-100 border-2 border-purple-200 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-info text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Need Help?</h4>
                        <p class="text-gray-700 text-sm">
                            Click on any event to view participant details and payment information. 
                            If you have pending payments, please complete them to secure your registration.
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>