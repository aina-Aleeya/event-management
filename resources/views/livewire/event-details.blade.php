{{-- resources/views/event/show.blade.php --}}
<div class="relative w-full min-h-screen bg-white">

    {{-- HERO --}}
    @if (!empty($event->posters) && is_array($event->posters))
        @php $firstPoster = $event->posters[0] ?? null; @endphp
        @if ($firstPoster)
            <div class="relative h-[380px] overflow-hidden">
                <div class="absolute inset-0 bg-cover bg-center"
                     style="background-image: url('{{ asset('storage/' . $firstPoster) }}');">
                </div>
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
            </div>
        @endif
    @endif

    {{-- MAIN CARD --}}
    <div class="relative max-w-7xl mx-auto -mt-40 bg-white rounded-2xl shadow-xl p-6 md:p-10 space-y-8 z-20">

        {{-- TITLE --}}
        <div class="flex items-start justify-between gap-4 flex-wrap border-b pb-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $event->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $event->venue ?? '' }} • {{ $event->city ?? '' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="uppercase text-sm font-semibold text-red-600">{{ $event->event_type }}</span>
            </div>
        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- LEFT SIDE --}}
            <div class="lg:col-span-8 space-y-8">

                {{-- GALLERY --}}
                @if (!empty($event->posters) && is_array($event->posters))
                    <div x-data="fadeGallery({{ json_encode($event->posters) }})"
                        x-init="init()"
                        @mouseenter="pause()"
                        @mouseleave="play()"
                        class="relative w-full aspect-video rounded-2xl overflow-hidden shadow bg-gray-900 group">

                        {{-- Slides --}}
                        <template x-for="(img, idx) in images" :key="idx">
                            <div x-show="current === idx"
                                x-transition:enter="transition-opacity duration-700"
                                x-transition:leave="transition-opacity duration-500"
                                class="absolute inset-0">
                                <img :src="imgUrl(img)"
                                    class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-black/20"></div>
                            </div>
                        </template>

                        {{-- Arrows --}}
                        <div class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition">
                            <button @click="prev()" class="p-2 rounded-full bg-black/40 text-white hover:bg-black/60">
                                ‹
                            </button>
                            <button @click="next()" class="p-2 rounded-full bg-black/40 text-white hover:bg-black/60">
                                ›
                            </button>
                        </div>

                        {{-- Dots --}}
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                            <template x-for="(_, i) in images" :key="i">
                                <button @click="goTo(i)"
                                    :class="current === i ? 'w-6 bg-white' : 'w-2.5 bg-white/60'"
                                    class="h-2.5 rounded-full transition-all"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Gallery Script --}}
                    <script>
                        function fadeGallery(posters) {
                            return {
                                images: posters || [],
                                current: 0,
                                timer: null,
                                interval: 4000,
                                init() {
                                    this.images = this.images.map(p =>
                                        p.startsWith('http')
                                            ? p
                                            : '{{ asset('storage') }}' + '/' + p.replace(/^\/+/, '')
                                    );
                                    this.play();
                                },
                                imgUrl(p) { return p; },
                                play() { this.stop(); this.timer = setInterval(() => this.next(), this.interval); },
                                stop() { if (this.timer) clearInterval(this.timer); },
                                pause() { this.stop(); },
                                next() { this.current = (this.current + 1) % this.images.length; },
                                prev() { this.current = (this.current - 1 + this.images.length) % this.images.length; },
                                goTo(i) { this.current = i; }
                            }
                        }
                    </script>
                @endif

                {{-- DESCRIPTION --}}
                <div class="bg-white p-6 rounded-2xl border shadow-sm">
                    <h3 class="text-xl font-semibold mb-3">About This Event</h3>
                    <div class="prose text-gray-700 max-w-none">
                        {!! $event->description !!}
                    </div>
                </div>

                {{-- CATEGORIES --}}
                @if (!empty($event->categories))
                    @php
                        $categories = is_array($event->categories)
                            ? $event->categories
                            : explode(',', $event->categories);
                    @endphp
                    <div class="bg-white p-6 rounded-2xl border shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Categories</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($categories as $cat)
                                <span class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-full">
                                    {{ trim($cat) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ORGANIZER CONTACT --}}
                <div class="bg-slate-900 rounded-2xl p-6 text-white">
                    <h4 class="text-lg font-bold mb-4">Organizer Contact</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <a href="mailto:{{ $event->contact_email }}" class="flex items-center gap-3 p-4 bg-white/10 rounded-lg hover:bg-white/20 transition">
                            <i class="fa-regular fa-envelope text-red-500"></i>
                            <div>
                                <p class="text-xs text-gray-300 tracking-wide">Email</p>
                                <p>{{ $event->contact_email }}</p>
                            </div>
                        </a>

                        <a href="tel:{{ $event->contact_phone }}" class="flex items-center gap-3 p-4 bg-white/10 rounded-lg hover:bg-white/20 transition">
                            <i class="fa-solid fa-phone text-red-500"></i>
                            <div>
                                <p class="text-xs text-gray-300 tracking-wide">Phone</p>
                                <p>{{ $event->contact_phone }}</p>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="lg:col-span-4">
                <aside class="lg:sticky lg:top-24 space-y-6">

                    {{-- QR + Copy --}}
                    <div class="bg-gray-50 border rounded-xl p-5 shadow-sm text-center space-y-2">
                        @if(!empty($event->qr_code))
                            <img src="{{ $event->qr_code }}" class="w-24 h-24 mx-auto mb-2" alt="QR Code">
                        @endif
                        <p class="text-xs text-gray-600">Scan this QR or copy the link</p>
                        <button 
                            x-data="{ copied:false }"
                            @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied=true; setTimeout(()=>copied=false,1500)"
                            class="mx-auto text-xs text-gray-600 hover:text-black transition flex items-center gap-1">

                            <i class="fa-solid fa-link text-sm"></i>

                            <span x-show="!copied">Copy Link</span>
                            <span x-show="copied" class="text-green-600 flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Copied!
                            </span>
                        </button>
                    </div>

                    {{-- Venue --}}
                    <div class="bg-white border rounded-xl p-4 shadow-sm">
                        <p class="font-semibold text-red-500 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot"></i> Venue
                        </p>
                        <p class="text-gray-800 mt-1">{{ $event->venue }}, {{ $event->city }}</p>
                    </div>

                    {{-- Date & Time --}}
                    <div class="bg-white border rounded-xl p-4 shadow-sm">
                        <p class="font-semibold text-red-500 flex items-center gap-2">
                            <i class="fa-regular fa-calendar"></i> Date & Time
                        </p>
                        <p class="text-gray-800 mt-1">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                            @if($event->end_date) – {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }} @endif
                            <br>
                            @if($event->start_time)
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                @if($event->end_time) – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }} @endif
                            @endif
                        </p>
                    </div>

                    {{-- Entry Fee --}}
                    <div class="bg-white border rounded-xl p-4 shadow-sm">
                        <p class="font-semibold text-red-500 flex items-center gap-2">
                            <i class="fa-regular fa-credit-card"></i> Entry Fee
                        </p>
                        <p class="mt-1">{{ $event->entry_fee ? 'RM '.number_format($event->entry_fee,2) : 'Free Entry' }}</p>
                    </div>

                    {{-- Register --}}
                    <a href="{{ route('peserta.form', ['id'=>$event->id]) }}"
                       class="block text-center px-5 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold shadow">
                        <i class="fa-solid fa-ticket mr-2"></i> Register Now
                    </a>

                </aside>
            </div>

        </div>

    </div>

</div>
