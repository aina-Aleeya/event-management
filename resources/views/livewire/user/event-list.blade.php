<section class="w-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse ($events as $event)
            <a href="{{ route('ads.click', ['id' => $event->id]) }}"
                class="group relative flex flex-col bg-white rounded-2xl hover:shadow-2xl 
                    border border-gray-200 transition-all duration-300 overflow-hidden cursor-pointer transform hover:-translate-y-2">

                {{-- Image Section --}}
                <div class="relative w-full h-56 overflow-hidden bg-gradient-to-br from-purple-100 to-pink-100">
                    <img src="{{ !empty($event->posters) ? asset('storage/' . $event->posters[0]) : asset('img/sample-event.jpg') }}"
                        alt="{{ $event->title }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                    {{-- Calendar Box --}}
                    <div
                        class="absolute top-4 left-4 bg-white rounded-xl text-center w-16 shadow-lg border border-purple-100">
                        @if ($event->start_date)
                            <div
                                class="bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-xs uppercase py-1 rounded-t-xl">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('M') }}
                            </div>
                            <div class="text-gray-900 text-2xl font-bold py-2">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                            </div>
                        @else
                            <div class="text-gray-400 text-xs p-3">TBA</div>
                        @endif
                    </div>

                    {{-- Category Badge --}}
                    @if ($event->event_type)
                        <div class="absolute top-4 right-4">
                            <span
                                class="px-3 py-1.5 bg-white/90 backdrop-blur-sm text-purple-600 text-xs font-bold rounded-full shadow-md border border-purple-100">
                                {{ $event->event_type }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex flex-col flex-grow p-5 space-y-3">
                    {{-- Title --}}
                    <h3
                        class="text-xl font-bold text-gray-900 line-clamp-2 group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-pink-600 group-hover:bg-clip-text transition-all">
                        {{ $event->title }}
                    </h3>

                    {{-- Info Grid --}}
                    <div class="space-y-2.5 flex-grow">
                        {{-- Venue --}}
                        <div class="flex items-start gap-2.5 text-sm text-gray-600">
                            <div
                                class="w-5 h-5 flex items-center justify-center bg-purple-100 rounded-lg flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-location-dot text-purple-600 text-xs"></i>
                            </div>
                            <span class="line-clamp-1">
                                {{ $event->venue ?? 'No venue' }}
                                @if ($event->city)
                                    <span class="text-gray-400">• {{ $event->city }}</span>
                                @endif
                            </span>
                        </div>

                        {{-- Entry Fee --}}
                        <div class="flex items-center gap-2.5 text-sm">
                            <div class="w-5 h-5 flex items-center justify-center bg-pink-100 rounded-lg flex-shrink-0">
                                <i class="fa-solid fa-ticket text-pink-600 text-xs"></i>
                            </div>
                            @if ($event->entry_fee)
                                <span class="font-bold text-pink-600">RM
                                    {{ number_format($event->entry_fee, 2) }}</span>
                            @else
                                <span class="font-bold text-green-600">FREE</span>
                            @endif
                        </div>
                    </div>

                    {{-- View Details Button --}}
                    <div class="pt-3 border-t border-gray-100">
                        <span class="flex items-center justify-center gap-2 w-full py-3 bg-purple-600 text-white font-bold rounded-xl hover:shadow-lg transform group-hover:scale-105 transition-all duration-200 pointer-events-none">
                            View Details
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-20">
                <div
                    class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full mb-6">
                    <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">No Events Yet</h3>
                <p class="text-gray-500 mb-6">Check back soon for exciting upcoming events!</p>
            </div>
        @endforelse
    </div>

    {{-- Show "See All Events" button only when limit is set (homepage) --}}
    @if ($limit)
        <div class="mt-16 text-center">
            <a href="{{ route('events.page') }}"
                class="inline-flex items-center gap-3 px-10 py-4 bg-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200">
                <span>See All Events</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    @endif

    {{-- Show pagination only when NOT limited (full events page) --}}
    @if (!$limit && method_exists($events, 'links'))
        <div class="mt-16">
            <div class="flex justify-center">
                {{ $events->links() }}
            </div>
        </div>
    @endif
</section>
