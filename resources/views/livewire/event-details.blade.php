<div class="relative w-full min-h-screen bg-white">

    {{-- Hero background --}}
    @if ($event->poster)
        <div class="relative h-[400px] overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('{{ asset('storage/' . $event->poster) }}');">
            </div>
            <div class="absolute inset-0 bg-black/30 backdrop-blur-md"></div>
        </div>
    @endif

    {{-- Event Content Card --}}
    <div class="relative max-w-6xl mx-auto -mt-60 bg-white rounded-2xl shadow-xl p-10 space-y-10 z-20">

        {{-- Title --}}
        <div class="flex items-center justify-between flex-wrap gap-3 border-b pb-4">
            <h1 class="text-4xl font-bold text-gray-800">{{ $event->title }}</h1>
            <span class="uppercase text-red-600 font-semibold">{{ $event->event_type }}</span>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Left: Poster --}}
            <div class="md:col-span-2">
                <img src="{{ asset('storage/' . $event->poster) }}" alt="Event Poster"
                     class="rounded-xl shadow-md w-full">
            </div>

            {{-- Right: QR & Info --}}
            <div class="space-y-5">

                {{-- QR Code --}}
                @if (!empty($event->qr_code))
                    <div class="bg-gray-50 border rounded-xl p-4 shadow-sm text-center">
                        <img src="{{ $event->qr_code }}" class="w-32 h-32 mx-auto" alt="QR Code">
                        <p class="text-sm text-gray-600 mt-2">Scan or share link</p>
                        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link copied!');"
                            class="mt-2 text-red-600 text-sm hover:underline flex items-center justify-center gap-1">
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
                        <i class="fa-regular fa-calendar text-red-500"></i> Date & Time
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
                <div class="bg-gray-50 border rounded-xl p-4 shadow-sm">
                    <p class="font-semibold text-gray-700">
                        <i class="fa-solid fa-money-bill-wave text-green-500"></i> Entry Fee
                    </p>
                    <p class="{{ $event->entry_fee ? 'text-gray-800' : 'text-gray-500' }}">
                        {{ $event->entry_fee ? 'RM '.number_format($event->entry_fee, 2) : 'Free Entry' }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Description --}}
        <div class="bg-gray-50 border rounded-xl p-6 shadow-sm text-gray-700 leading-relaxed">
            {!! $event->description !!}
        </div>

        {{-- Categories --}}
        @if (!empty($event->categories))
            @php
                $categories = is_array($event->categories)
                    ? $event->categories
                    : explode(',', $event->categories);
            @endphp
            <div class="mt-6">
                <h3 class="text-gray-800 font-semibold mb-2 text-lg">Event Categories</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $cat)
                        <span class="px-3 py-1 rounded-full text-sm font-medium text-white 
                                     bg-gradient-to-r from-red-400 via-pink-500 to-yellow-400
                                     shadow-sm hover:scale-105 transition transform">
                            {{ trim($cat) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Contact Info --}}
        <div class="mt-8 bg-gray-50 border rounded-xl p-6 shadow-sm">
            <h3 class="text-gray-800 font-semibold text-lg mb-2">Contact Information</h3>
            <p class="text-gray-700 mb-1">
                📧 Email: <a href="mailto:{{ $event->contact_email }}" class="text-red-600 hover:underline">{{ $event->contact_email }}</a>
            </p>
            <p class="text-gray-700">
                📞 Phone: <a href="tel:{{ $event->contact_phone }}" class="text-red-600 hover:underline">{{ $event->contact_phone }}</a>
            </p>
        </div>

        {{-- Register Button --}}
        <div class="flex justify-end pt-4">
            <a href="{{ route('peserta.form', ['id' => $event->id]) }}"
               class="flex items-center gap-2 bg-red-300 hover:bg-red-600 text-gray-900 px-6 py-3 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                <i class="fa-solid fa-ticket"></i> Register Now
            </a>
        </div>

    </div>
</div>
