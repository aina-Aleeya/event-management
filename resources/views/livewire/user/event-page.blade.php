<div class="min-h-screen bg-gradient-to-b from-purple-50 via-white to-pink-50">
    
    {{-- Enhanced Banner Carousel --}}
    <section x-data="carousel({{ $banners->count() }})" class="relative w-full h-[400px] md:h-[500px] overflow-hidden shadow-xl">

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

                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

                    {{-- Content --}}
                    <div class="absolute inset-0 flex items-end">
                        <div class="w-full px-6 md:px-16 lg:px-24 pb-12 md:pb-16">
                            <div class="max-w-3xl">
                                {{-- Badge --}}
                                @if($event->event_type)
                                    <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold uppercase rounded-full mb-4 shadow-lg">
                                        {{ $event->event_type }}
                                    </span>
                                @endif
                                
                                {{-- Title --}}
                                <h2 class="text-3xl md:text-5xl font-extrabold leading-tight text-white drop-shadow-2xl mb-4">
                                    {{ $event->title }}
                                </h2>
                                
                                {{-- Location --}}
                                <div class="flex items-center gap-3 text-white/90 mb-6">
                                    <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full">
                                        <i class="fa-solid fa-location-dot text-pink-400"></i>
                                        <span class="font-medium">{{ $event->venue ?? 'No venue' }}</span>
                                    </div>
                                    @if($event->city)
                                        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full">
                                            <i class="fa-solid fa-city text-purple-400"></i>
                                            <span class="font-medium">{{ $event->city }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- CTA Button --}}
                                <a href="{{ route('event.details', ['id' => $event->id]) }}"
                                   class="inline-flex items-center gap-3 bg-white text-purple-600 font-bold px-8 py-4 rounded-xl
                                          hover:shadow-2xl hover:-translate-y-1 transition-all duration-200">
                                    <span>Explore Event</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Clone first slide for seamless loop --}}
            <div class="w-full flex-shrink-0 relative h-full">
                <img src="{{ asset('storage/' . $banners[0]->posters[0]) }}"
                     alt="{{ $banners[0]->title }}"
                     class="w-full h-full object-cover object-center select-none" />
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
            </div>
        </div>

        {{-- Navigation Controls --}}
        <button @click="prev()" 
                class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/80 backdrop-blur-sm hover:bg-white rounded-full shadow-xl hover:shadow-2xl transition-all group">
            <i class="fa fa-chevron-left text-gray-700 group-hover:text-purple-600 transition-colors"></i>
        </button>

        <button @click="next()" 
                class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/80 backdrop-blur-sm hover:bg-white rounded-full shadow-xl hover:shadow-2xl transition-all group">
            <i class="fa fa-chevron-right text-gray-700 group-hover:text-purple-600 transition-colors"></i>
        </button>

        {{-- Indicators --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 bg-black/30 backdrop-blur-sm px-4 py-2 rounded-full">
            @foreach($banners as $i => $event)
                <button :class="active === {{ $i }} ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/70'"
                        class="h-2 rounded-full transition-all duration-300"
                        @click="goTo({{ $i }})"></button>
            @endforeach
        </div>
    </section>

    {{-- Search Section --}}
    <div class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 md:px-16 lg:px-24 py-8">
            <livewire:header-search />
        </div>
    </div>

    {{-- Events Section --}}
    <section class="py-16 px-6 md:px-16 lg:px-24">
        <div class="max-w-7xl mx-auto">
            {{-- Section Header --}}
            <div class="mb-12 text-center">
                <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">
                    Discover Events
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Find the perfect event for you from our curated collection
                </p>
            </div>

            {{-- Event List with 8 items per page --}}
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