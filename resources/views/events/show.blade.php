<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-lg mt-6">
        {{-- Event Poster --}}
        @if ($event->posters)
            <img src="{{ asset('storage/' . $event->posters) }}" alt=alt="{{ $event->title }}"
                class="w-full max-h-96 object-contain rounded-lg mb-4">
        @endif

        {{-- Title and Dates --}}
        <h1 class="text-3xl font-bold mb-2">{{ $event->title }}</h1>
        <p class="text-gray-600 mb-4">
            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
            –
            {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
        </p>

        {{-- Time --}}
        {{-- <p class="text-gray-600 mb-4">
            {{ \Carbon\Carbon::parse($event->start_time)}}
            –
            {{ \Carbon\Carbon::parse($event->end_time)}}
        </p> --}}

        {{-- Description --}}
        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">Description</h2>
            <div class="prose max-w-none">
                {!! $event->description !!}
            </div>
        </div>

        {{-- Venue & Organizer --}}
        @if ($event->venue)
            <p class="text-gray-700"><strong>Venue:</strong> {{ $event->venue }}</p>
        @endif

        @if ($event->organizer_name)
            <p class="text-gray-700"><strong>Organizer:</strong> {{ $event->organizer_name }}</p>
        @endif

        {{-- Ad Duration --}}
        <div class="mt-6">
            <p class="text-sm text-gray-500">Ad Duration:</p>
            <p class="text-gray-700 font-medium">
                {{ \Carbon\Carbon::parse($event->ads_start_date)->format('M d, Y') }}
                –
                {{ \Carbon\Carbon::parse($event->ads_end_date)->format('M d, Y') }}
            </p>
        </div>

        {{-- QR Code + Link --}}
        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                @if ($event->qr_code)
                    <img src="{{ asset('storage/' . $event->qr_code) }}" alt="Event QR" class="w-24 h-24">
                @endif
                <a href="{{ $event->event_link }}" class="text-blue-600 underline text-sm break-all">
                    {{ $event->event_link }}
                </a>
            </div>
        </div>

        {{-- Register Button (optional) --}}
        <div class="mt-8">
            <a href="#" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                Register Now
            </a>
        </div>
    </div>
</x-layouts.app>