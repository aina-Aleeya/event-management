<section class="bg-white w-full py-16 px-4 sm:px-8 md:px-12 lg:px-20">
    <div class="max-w-7xl mx-auto w-full">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center tracking-tight">
            Upcoming Events
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse ($events as $event)
                <div onclick="window.location='{{ route('event.details', ['id' => $event->id]) }}'" class="group relative flex flex-col bg-white rounded-xl shadow-md hover:shadow-xl 
                                transition-all duration-300 overflow-hidden cursor-pointer">

                    {{-- Image Section --}}
                    <div class="relative w-full h-48 overflow-hidden">
                        <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('img/sample-event.jpg') }}"
                            alt="{{ $event->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent"></div>
                    </div>

                    {{-- Calendar style date box --}}
                    <div class="absolute top-3 left-3 bg-white rounded-lg text-center shadow-md w-14">
                        @if($event->start_date)
                            <div class="text-purple-500 font-bold text-sm uppercase">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('M') }}
                            </div>
                            <div class="text-gray-900 text-lg font-bold leading-none">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                            </div>
                        @else
                            <div class="text-gray-400 text-xs p-2">TBA</div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex flex-col flex-grow p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                            {{ $event->title }}
                        </h3>

                        <p class="text-sm text-gray-500 mb-2 line-clamp-1">
                            {{ $event->location ?? 'No location' }}
                        </p>

                        <div class="flex items-center text-xs text-gray-500 mb-3">
                            <i class="fa fa-calendar mr-1"></i>
                            @if($event->start_date)
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                            @else
                                <span>Date not set</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-auto">
                            <a href="{{ route('event.details', ['id' => $event->id]) }}" class="text-xs font-semibold uppercase bg-purple-300 text-gray-900 px-3 py-1.5 rounded-md 
                                          hover:bg-sky-300 transition-all">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center col-span-full">
                    No events yet. Create one to get started!
                </p>
            @endforelse
        </div>


        @if ($limit)
            <div class="mt-10 text-center">
                <a href="{{ route('events.page') }}"
                    class="inline-block bg-purple-300 hover:bg-sky-300 text-gray-900 font-semibold px-5 py-2 rounded-lg shadow transition">
                    See More Events →
                </a>
            </div>
        @endif

        @if (method_exists($events, 'links'))
            <div class="mt-10">
                {{ $events->links() }}
            </div>
        @endif

    </div>
</section>