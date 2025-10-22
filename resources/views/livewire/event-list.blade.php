<section class="bg-white py-16 px-8">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center tracking-tight">
            Upcoming Events
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-8">
            @forelse ($events as $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition transform hover:-translate-y-1">
                    {{-- Event Image --}}
                    <div class="w-full h-48 overflow-hidden">
                        <img src="{{ $event->poster ? asset('storage/'.$event->poster) : asset('img/sample-event.jpg') }}"
                             alt="{{ $event->title }}"
                             class="w-full h-full object-cover transition duration-300 hover:scale-105">
                    </div>

                    {{-- Event Info --}}
                    <div class="p-5 text-left">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $event->title }}
                        </h3>

                        <p class="text-sm text-gray-500 mb-4">
                            {{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('d F Y') : 'No date' }}
                            @if ($event->location)
                                <span class="mx-1 text-gray-400">-</span> {{ $event->location }}
                            @endif
                        </p>

                        <a href="{{ route('event.details', ['id' => $event->id]) }}"
                           class="inline-block bg-purple-300 text-gray-900 font-extralight px-2 py-1 rounded-md shadow-sm 
                                  hover:bg-sky-300 transition duration-200 ease-in-out">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center col-span-full">
                    No events yet. Create one to get started!
                </p>
            @endforelse
        </div>
    </div>
</section>

