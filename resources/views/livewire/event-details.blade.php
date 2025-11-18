<div class="relative w-full min-h-screen bg-white">

    {{-- Hero background --}}
    @if (!empty($event->posters) && is_array($event->posters))
        @php
            $firstPoster = $event->posters[0] ?? null;
        @endphp
        @if ($firstPoster)
            <div class="relative h-[400px] overflow-hidden">
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('{{ asset('storage/' . $firstPoster) }}');">
                </div>
                <div class="absolute inset-0 bg-black/30 backdrop-blur-md"></div>
            </div>
        @endif
    @endif

    {{-- Event Content Card --}}
    <div class="relative max-w-6xl mx-auto -mt-60 bg-white rounded-2xl shadow-xl p-10 space-y-10 z-20">

        {{-- Title --}}
        <div class="flex items-center justify-between flex-wrap gap-3 border-b pb-4">
            <h1 class="text-4xl font-bold text-gray-800">{{ $event->title }}</h1>
            <span class="uppercase text-blue-600 font-semibold">{{ $event->event_type }}</span>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Left: Poster --}}
            @if (!empty($event->posters) && is_array($event->posters))
                <div x-data="fadeCarousel({{ count($event->posters) }})" x-init="init()" @mouseenter="pause()" @mouseleave="play()"
                    class="relative md:col-span-2">

                    <!-- Image Wrapper -->
                    <div class="relative w-full h-96 overflow-hidden rounded-xl bg-gray-100 shadow-md">

                        @foreach ($event->posters as $index => $img)
                            <img src="{{ asset('storage/' . $img) }}"
                                class="absolute inset-0 w-full h-full object-contain transition-opacity duration-700"
                                :class="active === {{ $index }} ? 'opacity-100' : 'opacity-0'">
                        @endforeach

                    </div>

                    <!-- Prev Button -->
                    <button @click="prev()"
                        class="absolute top-1/2 left-4 -translate-y-1/2 bg-black/50 text-white px-3 py-2 rounded-full">
                        ‹
                    </button>

                    <!-- Next Button -->
                    <button @click="next()"
                        class="absolute top-1/2 right-4 -translate-y-1/2 bg-black/50 text-white px-3 py-2 rounded-full">
                        ›
                    </button>

                    <!-- Dots -->
                    <div class="flex justify-center mt-3 space-x-2">
                        @foreach ($event->posters as $index => $p)
                            <button class="w-3 h-3 rounded-full"
                                :class="active === {{ $index }} ? 'bg-blue-500' : 'bg-gray-300'"
                                @click="goTo({{ $index }})"></button>
                        @endforeach
                    </div>

                </div>

                <!-- Alpine Logic -->
                <script>
                    function fadeCarousel(count) {
                        return {
                            active: 0,
                            count: count,
                            interval: null,

                            init() {
                                this.play();
                            },

                            play() {
                                this.interval = setInterval(() => {
                                    this.next();
                                }, 4000);
                            },

                            pause() {
                                clearInterval(this.interval);
                            },

                            next() {
                                this.active = (this.active + 1) % this.count;
                            },

                            prev() {
                                this.active = (this.active - 1 + this.count) % this.count;
                            },

                            goTo(i) {
                                this.active = i;
                            }
                        }
                    }
                </script>
            @endif

            {{-- Right: QR & Info --}}
            <div class="space-y-5">
                {{-- QR Code --}}
                @if (!empty($event->qr_code))
                    <div class="bg-gray-50 border rounded-xl p-4 shadow-sm text-center">
                        <img src="{{ $event->qr_code }}" class="w-32 h-32 mx-auto" alt="QR Code">
                        <p class="text-sm text-gray-600 mt-2">Scan or share link</p>
                        <button
                            onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link copied!');"
                            class="mt-2 text-purple-600 text-sm hover:underline flex items-center justify-center gap-1">
                            <i class="fa-solid fa-copy"></i> Copy Link
                        </button>
                    </div>
                @endif

                {{-- Venue --}}
                <div class="bg-gray-50 border rounded-xl p-4 shadow-sm">
                    <p class="font-semibold text-gray-700">
                        <i class="fa-solid fa-location-dot text-red-500"></i> Venue
                    </p>
                    <p class="text-gray-800">{{ $event->venue }}, {{ $event->city }}</p>
                </div>

                {{-- Date & Time --}}
                <div class="bg-gray-50 border rounded-xl p-4 shadow-sm">
                    <p class="font-semibold text-gray-700">
                        <i class="fa-regular fa-calendar text-pink-500"></i> Date & Time
                    </p>
                    <p class="text-gray-800">
                        {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} –
                        {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                        <br>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} –
                        {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                    </p>
                </div>

                {{-- Entry Fee --}}
                @if ($event->entry_fee)
                    <div class="bg-gray-50 border rounded-xl p-4 shadow-sm">
                        <p class="font-semibold text-gray-700">
                            <i class="fa-solid fa-money-bill-wave text-green-500"></i> Entry Fee
                        </p>
                        <p class="text-gray-800">
                            RM {{ number_format($event->entry_fee, 2) }}
                        </p>
                    </div>
                @else
                    <div class="bg-gray-50 border rounded-xl p-4 shadow-sm">
                        <p class="font-semibold text-gray-700">
                            <i class="fa-solid fa-money-bill-wave text-green-500"></i> Entry Fee
                        </p>
                        <p class="text-gray-500">Free Entry</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-gray-50 border rounded-xl p-6 shadow-sm text-gray-700 leading-relaxed">
            {!! $event->description !!}
        </div>

        {{-- Categories --}}
        @if (!empty($event->categories))
            @php
                $categories = is_array($event->categories) ? $event->categories : explode(',', $event->categories);
            @endphp
            <div class="flex flex-wrap gap-2">
                @foreach ($categories as $cat)
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                        {{ trim($cat) }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Contact Info --}}
        <div class="border-t pt-6 text-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Contact Information</h3>
            <p class="text-gray-700">
                📧 {{ $event->contact_email }} <br>
                📞 {{ $event->contact_phone }}
            </p>
        </div>

        {{-- Register Button (moved below, right-aligned) --}}
        <div class="flex justify-end pt-4">
            <a href="{{ route('peserta.form', ['id' => $event->id]) }}"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-300 text-gray-900 px-6 py-3 rounded-lg shadow-md transition">
                <i class="fa-solid fa-ticket"></i> Register Now
            </a>
        </div>

    </div>
</div>
