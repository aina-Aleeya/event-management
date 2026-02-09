<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50">
    
    {{-- Stylish Banner Carousel --}}
    <section x-data="carousel({{ $banners->count() }})" class="relative w-full h-[450px] md:h-[550px] overflow-hidden shadow-2xl">

        {{-- Slides Wrapper --}}
        <div class="flex transition-transform duration-700 ease-out h-full"
             :style="`transform: translateX(-${active * 100}%);`"
             @mouseenter="stopAutoSlide()"
             @mouseleave="startAutoSlide()">

            {{-- Slides --}}
            @foreach($banners as $event)
                <div class="w-full flex-shrink-0 relative h-full group">
                    <img src="{{ asset('storage/' . $event->posters[0]) }}"
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />

                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                    {{-- Content --}}
                    <div class="absolute inset-0 flex items-end">
                        <div class="w-full px-8 md:px-16 pb-12">
                            <div class="max-w-3xl">
                                @if($event->event_type)
                                    <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold uppercase rounded-full mb-4 shadow-lg">
                                        {{ $event->event_type }}
                                    </span>
                                @endif
                                
                                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-lg">
                                    {{ $event->title }}
                                </h2>
                                
                                <div class="flex flex-wrap gap-3 mb-6">
                                    <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg text-white">
                                        <i class="fa-solid fa-location-dot text-pink-300"></i>
                                        <span class="font-medium">{{ $event->venue ?? 'No venue' }}</span>
                                    </div>
                                    @if($event->city)
                                        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg text-white">
                                            <i class="fa-solid fa-city text-purple-300"></i>
                                            <span class="font-medium">{{ $event->city }}</span>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('event.details', ['id' => $event->id]) }}"
                                   class="inline-flex items-center gap-2 bg-white text-purple-600 font-bold px-6 py-3 rounded-lg hover:bg-purple-600 hover:text-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                    <span>View Details</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Clone first slide --}}
            <div class="w-full flex-shrink-0 relative h-full">
                <img src="{{ asset('storage/' . $banners[0]->posters[0]) }}"
                     alt="{{ $banners[0]->title }}"
                     class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            </div>
        </div>

        {{-- Navigation Buttons --}}
        <button @click="prev()" 
                class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-xl hover:scale-110 transition-all duration-300 group">
            <i class="fa fa-chevron-left text-gray-700 group-hover:text-purple-600 transition-colors"></i>
        </button>

        <button @click="next()" 
                class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-xl hover:scale-110 transition-all duration-300 group">
            <i class="fa fa-chevron-right text-gray-700 group-hover:text-purple-600 transition-colors"></i>
        </button>

        {{-- Indicators --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 bg-black/30 backdrop-blur-sm px-4 py-2 rounded-full">
            @foreach($banners as $i => $event)
                <button :class="active === {{ $i }} ? 'w-8 bg-white' : 'w-2 bg-white/60 hover:bg-white/80'"
                        class="h-2 rounded-full transition-all duration-300"
                        @click="goTo({{ $i }})"></button>
            @endforeach
        </div>
    </section>

    {{-- Search Section --}}
    <div class="bg-white shadow-md border-b sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-6 md:px-12 py-6">
            <livewire:header-search />
        </div>
    </div>

    {{-- Events Section --}}
    <section class="py-16 px-6 md:px-12">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1.5 bg-purple-100 text-purple-600 text-sm font-semibold rounded-full mb-3">
                    Featured Events
                </span>
                <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-3">
                    Upcoming Events
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Discover amazing events happening around you
                </p>
            </div>

            <livewire:user.event-list :perPage="8" />
        </div>
    </section>

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

                    if (this.active > this.total - 1) {
                        setTimeout(() => {
                            this.active = 0;
                            const wrapper = this.$el.querySelector('div.flex');
                            wrapper.classList.remove('transition-transform');
                            requestAnimationFrame(() => {
                                wrapper.classList.add('transition-transform');
                                this.isTransitioning = false;
                            });
                        }, 700);
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