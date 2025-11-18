<x-layouts.app :title="'All Events'">

    {{-- Banner Carousel --}}
    <section x-data="{ active: 0 }" class="relative w-full h-[300px] md:h-[400px] overflow-hidden">
        @foreach ($banners as $index => $event)
            <div x-show="active === {{ $index }}" x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="absolute inset-0">
            <img src="{{ asset('storage/' . $event->posters) }}"
                 alt="{{ $event->title }}"
                 class="w-full h-full object-cover object-center select-none"
                 loading="lazy" />

                <div class="absolute inset-0 bg-black/40"></div>

                {{--Overlay Text --}}
                <div class="absolute left-5 md:left-8 bottom-10 max-w-lg text-left text-white">
                    <h2 class="text-2xl md:text-4xl font-extrabold leading-tight drop-shadow-lg mb-3">
                        {{ $event->title }}
                    </h2>
                    <p class="text-base md:text-lg text-gray-200 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-purple-300"></i>
                        {{ $event->venue ?? 'No venue' }},
                        <span class="text-gray-200">{{ $event->city ?? 'No city' }}</span>
                    </p>


                    {{-- Direct Laravel route --}}
                    <a href="{{ route('event.details', ['id' => $event->id]) }}"
                        class="inline-block bg-white text-purple-700 font-semibold px-3 py-1.5 rounded-lg
                                          hover:bg-purple-100 hover:-translate-y-0.5 transition-all duration-200 shadow-lg">
                        Explore Event
                    </a>
                </div>
            </div>
        @endforeach

        {{-- Controls --}}
        <button @click="active = (active - 1 + {{ $banners->count() }}) % {{ $banners->count() }}"
            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/60 hover:bg-white rounded-full p-2 shadow">
            <i class="fa fa-chevron-left text-gray-700"></i>
        </button>

        <button @click="active = (active + 1) % {{ $banners->count() }}"
            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/60 hover:bg-white rounded-full p-2 shadow">
            <i class="fa fa-chevron-right text-gray-700"></i>
        </button>
    </section>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carousel', () => ({
                active: 0,
                init() {
                    setInterval(() => {
                        this.active = (this.active + 1) % {{ $banners->count() }};
                    }, 5000);
                }
            }))
        })
    </script>


    <livewire:header-search />

    <livewire:event-list />

</x-layouts.app>