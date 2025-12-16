<div>
    <section x-data="carousel({{ $banners->count() }})" class="relative w-full h-[300px] md:h-[400px] overflow-hidden">

        {{-- Slides Wrapper --}}
        <div class="flex transition-transform duration-700 ease-in-out h-full"
             :style="`transform: translateX(-${active * 100}%);`"
             @mouseenter="stopAutoSlide()"
             @mouseleave="startAutoSlide()">

            {{-- Slides --}}
            @foreach($banners as $event)
                <div class="w-full flex-shrink-0 relative h-full">
                    <img src="{{ asset('storage/' . $event->posters[0]) }}"
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover object-center select-none" />

                    <div class="absolute inset-0 bg-black/40"></div>

                    <div class="absolute left-5 md:left-8 bottom-10 max-w-lg text-left text-white">
                        <h2 class="text-2xl md:text-4xl font-extrabold leading-tight drop-shadow-lg mb-3">
                            {{ $event->title }}
                        </h2>
                        <p class="text-base md:text-lg text-gray-200 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-red-600"></i>
                            {{ $event->venue ?? 'No venue' }},
                            <span class="text-gray-200">{{ $event->city ?? 'No city' }}</span>
                        </p>

                        <a href="{{ route('event.details', ['id' => $event->id]) }}"
                           class="inline-block bg-white text-red-700 font-semibold px-3 py-1.5 rounded-lg
                                  hover:bg-purple-100 hover:-translate-y-0.5 transition-all duration-200 shadow-lg">
                            Explore Event
                        </a>
                    </div>
                </div>
            @endforeach

            {{-- Clone first slide for seamless loop --}}
            <div class="w-full flex-shrink-0 relative h-full">
                <img src="{{ asset('storage/' . $banners[0]->posters[0]) }}"
                     alt="{{ $banners[0]->title }}"
                     class="w-full h-full object-cover object-center select-none" />
                <div class="absolute inset-0 bg-black/40"></div>
            </div>
        </div>

        {{-- Controls --}}
        <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/60 hover:bg-white rounded-full p-2 shadow">
            <i class="fa fa-chevron-left text-gray-700"></i>
        </button>

        <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/60 hover:bg-white rounded-full p-2 shadow">
            <i class="fa fa-chevron-right text-gray-700"></i>
        </button>

        {{-- Indicators --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
            @foreach($banners as $i => $event)
                <span :class="active === {{ $i }} ? 'bg-white w-2 h-2' : 'bg-white/50 w-2 h-2'"
                      class="rounded-full transition-all duration-300 cursor-pointer"
                      @click="goTo({{ $i }})"></span>
            @endforeach
        </div>
    </section>

    {{-- Livewire components --}}
    <livewire:header-search />
    <livewire:user.event-list />

    <script>
        function carousel(totalSlides) {
            return {
                active: 0,
                total: totalSlides,
                timer: null,
                isTransitioning: false,

                init() {
                    this.startAutoSlide();
                },

                startAutoSlide() {
                    this.stopAutoSlide();
                    this.timer = setInterval(() => this.next(), 5000);
                },

                stopAutoSlide() {
                    if (this.timer) clearInterval(this.timer);
                },

                goTo(index) {
                    this.active = index;
                    this.resetInterval();
                },

                resetInterval() {
                    this.startAutoSlide();
                },

                next() {
                    if (this.isTransitioning) return;
                    this.isTransitioning = true;

                    this.active++;

                    // When reaching the cloned slide
                    if (this.active > this.total - 1) {
                        setTimeout(() => {
                            // jump back to first slide without animation
                            this.active = 0;
                            const wrapper = document.querySelector('[x-data]');
                            wrapper.querySelector('div.flex').classList.remove('transition-transform');
                            requestAnimationFrame(() => {
                                wrapper.querySelector('div.flex').classList.add('transition-transform');
                                this.isTransitioning = false;
                            });
                        }, 700); // match transition duration
                    } else {
                        setTimeout(() => this.isTransitioning = false, 700);
                    }
                },

                prev() {
                    if (this.isTransitioning) return;
                    this.isTransitioning = true;

                    if (this.active === 0) {
                        this.active = this.total - 1;
                        setTimeout(() => this.isTransitioning = false, 700);
                    } else {
                        this.active--;
                        setTimeout(() => this.isTransitioning = false, 700);
                    }
                }
            }
        }
    </script>
</div>
