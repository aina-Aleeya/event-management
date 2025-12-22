<div class="relative w-full min-h-screen bg-gradient-to-b from-purple-50 via-white to-pink-50">

    {{-- HERO SECTION --}}
    @if (!empty($event->posters) && is_array($event->posters))
        @php $firstPoster = $event->posters[0] ?? null; @endphp
        @if ($firstPoster)
            <div class="relative h-[500px] md:h-[600px] overflow-hidden">
                {{-- Background Image --}}
                <div class="absolute inset-0 bg-cover bg-center transform scale-110"
                    style="background-image: url('{{ asset('storage/' . $firstPoster) }}');">
                </div>
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

                {{-- Decorative Elements --}}
                <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-pink-500/20 rounded-full blur-3xl"></div>

                {{-- Hero Content --}}
                <div class="absolute inset-0 flex items-end">
                    <div class="w-full px-6 md:px-16 lg:px-24 pb-16">
                        <div class="max-w-4xl">
                            {{-- Event Type Badge --}}
                            <div class="mb-4">
                                <span
                                    class="inline-block px-5 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-sm font-bold uppercase rounded-full shadow-lg">
                                    {{ $event->event_type }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h1
                                class="text-4xl md:text-6xl font-extrabold text-white mb-6 drop-shadow-2xl leading-tight">
                                {{ $event->title }}
                            </h1>

                            {{-- Quick Info --}}
                            <div class="flex flex-wrap gap-4">
                                @if ($event->venue)
                                    <div
                                        class="flex items-center gap-2 bg-white/20 backdrop-blur-md px-5 py-3 rounded-full text-white">
                                        <i class="fa-solid fa-location-dot text-pink-300"></i>
                                        <span class="font-medium">{{ $event->venue }}, {{ $event->city }}</span>
                                    </div>
                                @endif

                                @if ($event->start_date)
                                    <div
                                        class="flex items-center gap-2 bg-white/20 backdrop-blur-md px-5 py-3 rounded-full text-white">
                                        <i class="fa-regular fa-calendar text-purple-300"></i>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</span>
                                    </div>
                                @endif

                                @if ($event->entry_fee)
                                    <div
                                        class="flex items-center gap-2 bg-white/20 backdrop-blur-md px-5 py-3 rounded-full text-white">
                                        <i class="fa-solid fa-ticket text-green-300"></i>
                                        <span class="font-medium">RM {{ number_format($event->entry_fee, 2) }}</span>
                                    </div>
                                @else
                                    <div
                                        class="flex items-center gap-2 bg-white/20 backdrop-blur-md px-5 py-3 rounded-full text-white">
                                        <i class="fa-solid fa-ticket text-green-300"></i>
                                        <span class="font-medium">FREE</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- MAIN CONTENT CARD --}}
    <div class="relative max-w-7xl mx-auto -mt-32 px-6 md:px-16 lg:px-24 pb-20 z-20">
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 space-y-10">

            {{-- GRID LAYOUT --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                {{-- LEFT SIDE (Main Content) --}}
                <div class="lg:col-span-8 space-y-10">

                    {{-- PHOTO GALLERY --}}
                    @if (!empty($event->posters) && is_array($event->posters))
                        <div x-data="fadeGallery({{ json_encode($event->posters) }})" x-init="init()" @mouseenter="pause()" @mouseleave="play()"
                            class="relative w-full aspect-video rounded-2xl overflow-hidden shadow bg-gray-900 group">

                            {{-- Slides --}}
                            <template x-for="(img, idx) in images" :key="idx">
                                <div x-show="current === idx" x-transition:enter="transition-opacity duration-700"
                                    x-transition:leave="transition-opacity duration-500" class="absolute inset-0">
                                    <img :src="imgUrl(img)" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-black/20"></div>
                                </div>
                            </template>

                            {{-- Arrows --}}
                            <div
                                class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition">
                                <button @click="prev()"
                                    class="p-2 rounded-full bg-black/40 text-white hover:bg-black/60">
                                    ‹
                                </button>
                                <button @click="next()"
                                    class="p-2 rounded-full bg-black/40 text-white hover:bg-black/60">
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
                                            p.startsWith('http') ?
                                            p :
                                            '{{ asset('storage') }}' + '/' + p.replace(/^\/+/, '')
                                        );
                                        this.play();
                                    },
                                    imgUrl(p) {
                                        return p;
                                    },
                                    play() {
                                        this.stop();
                                        this.timer = setInterval(() => this.next(), this.interval);
                                    },
                                    stop() {
                                        if (this.timer) clearInterval(this.timer);
                                    },
                                    pause() {
                                        this.stop();
                                    },
                                    next() {
                                        this.current = (this.current + 1) % this.images.length;
                                    },
                                    prev() {
                                        this.current = (this.current - 1 + this.images.length) % this.images.length;
                                    },
                                    goTo(i) {
                                        this.current = i;
                                    }
                                }
                            }
                        </script>
                    @endif

                    {{-- DESCRIPTION --}}
                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <span
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-info-circle text-white text-sm"></i>
                            </span>
                            About This Event
                        </h3>
                        <div
                            class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-100">
                            <div class="prose prose-lg max-w-none text-gray-700">
                                {!! $event->description !!}
                            </div>
                        </div>
                    </div>

                    {{-- CATEGORIES --}}
                    @if ($event->categories->count() > 0 || $event->customCategories->count() > 0)
                        <div class="space-y-4">
                            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                                <span
                                    class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-tags text-white text-sm"></i>
                                </span>
                                Event Categories
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                {{-- Default Categories --}}
                                @foreach ($event->categories as $category)
                                    <span
                                        class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-full shadow-md hover:shadow-lg transition-shadow">
                                        {{ $category->name }}
                                    </span>
                                @endforeach

                                {{-- Custom Categories --}}
                                @foreach ($event->customCategories as $customCategory)
                                    <span
                                        class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold rounded-full shadow-md hover:shadow-lg transition-shadow">
                                        {{ $customCategory->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ORGANIZER CONTACT --}}
                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <span
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-address-book text-white text-sm"></i>
                            </span>
                            Contact Organizer
                        </h3>
                        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 text-white shadow-xl">
                            <div class="grid md:grid-cols-2 gap-5">
                                <a href="mailto:{{ $event->contact_email }}"
                                    class="group flex items-center gap-4 p-5 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all hover:scale-105">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="fa-regular fa-envelope text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Email</p>
                                        <p class="font-medium truncate">{{ $event->contact_email }}</p>
                                    </div>
                                </a>

                                <a href="tel:{{ $event->contact_phone }}"
                                    class="group flex items-center gap-4 p-5 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all hover:scale-105">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-phone text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Phone</p>
                                        <p class="font-medium">{{ $event->contact_phone }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT SIDEBAR --}}
                <div class="lg:col-span-4">
                    <aside class="lg:sticky lg:top-24 space-y-6">

                        {{-- REGISTER CTA --}}
                        <div
                            class="bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 rounded-2xl p-8 text-white text-center shadow-2xl">
                            <div class="mb-6">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full mb-4">
                                    <i class="fa-solid fa-ticket text-3xl"></i>
                                </div>
                                <h4 class="text-2xl font-bold mb-2">Ready to Join?</h4>
                                <p class="text-white/90">Secure your spot now!</p>
                            </div>
                            <a href="{{ route('peserta.form', ['id' => $event->id]) }}"
                                class="block px-8 py-4 bg-white text-purple-600 font-bold rounded-xl hover:shadow-2xl hover:-translate-y-1 transition-all">
                                Register Now
                            </a>
                        </div>

                        {{-- QR CODE & SHARE --}}
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-lg text-center space-y-4">
                            <h4 class="font-bold text-gray-800 text-lg">Share This Event</h4>
                            @if (!empty($event->qr_code))
                                <div class="flex justify-center">
                                    <div
                                        class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border-2 border-purple-200">
                                        <img src="{{ $event->qr_code }}" class="w-32 h-32" alt="QR Code">
                                    </div>
                                </div>
                            @endif
                            <p class="text-sm text-gray-600">Scan QR code or copy link</p>
                            <button x-data="{ copied: false }"
                                @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied=true; setTimeout(()=>copied=false,2000)"
                                class="w-full px-5 py-3 bg-purple-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-link"></i>
                                <span x-show="!copied">Copy Link</span>
                                <span x-show="copied" class="flex items-center gap-2">
                                    <i class="fa-solid fa-check"></i> Copied!
                                </span>
                            </button>
                        </div>

                        {{-- EVENT DETAILS --}}
                        <div class="space-y-4">
                            {{-- Venue --}}
                            <div
                                class="bg-white border border-gray-200 rounded-2xl p-5 shadow-md hover:shadow-lg transition-shadow">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-location-dot text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                            Venue</p>
                                        <p class="text-gray-900 font-medium">{{ $event->venue }}</p>
                                        <p class="text-gray-600 text-sm">{{ $event->city }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Date & Time --}}
                            <div
                                class="bg-white border border-gray-200 rounded-2xl p-5 shadow-md hover:shadow-lg transition-shadow">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fa-regular fa-calendar text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                            Date & Time</p>
                                        <p class="text-gray-900 font-medium">
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                            @if (
                                                $event->end_date &&
                                                    \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') !==
                                                        \Carbon\Carbon::parse($event->end_date)->format('Y-m-d'))
                                                <span class="text-gray-500">to</span>
                                                {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                                            @endif
                                        </p>
                                        @if ($event->start_time)
                                            <p class="text-gray-600 text-sm">
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                                @if ($event->end_time)
                                                    - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Entry Fee --}}
                            <div
                                class="bg-white border border-gray-200 rounded-2xl p-5 shadow-md hover:shadow-lg transition-shadow">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-tag text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                            Entry Fee</p>
                                        <p class="text-gray-900 font-bold text-xl">
                                            {{ $event->entry_fee ? 'RM ' . number_format($event->entry_fee, 2) : 'FREE' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>
