<div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-2xl mt-6">
    {{-- Event Poster --}}
    @if ($event->poster)
    <img 
        src="{{ asset('storage/'.$event->poster) }}" 
        alt="Event Poster"
        class="w-full max-h-96 object-contain rounded-lg mb-4">
    @endif


    {{-- Title --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>

    {{-- Event Information --}}
    <div class="text-sm text-gray-600 space-y-2 mb-4">
        <p>
            <strong>Date:</strong> 
            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
        </p>
        @if ($event->location)
            <p><strong>Location:</strong> {{ $event->location }}</p>
        @endif
    </div>

    {{-- QR Code (toggle with Alpine.js) --}}
    @if ($event->qr_code)
        <div class="my-6" x-data="{ showQR: false }">
            <button 
                @click="showQR = !showQR" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <span x-show="!showQR">Show QR Code</span>
                <span x-show="showQR">Hide QR Code</span>
            </button>

            <div x-show="showQR" x-transition class="mt-4">
                <img src="{{ asset('storage/'.$event->qr_code) }}" 
                     alt="QR Code" 
                     class="w-40 mx-auto">
            </div>
        </div>
    @endif

    {{-- Event Link --}}
    @if ($event->link)
        <div class="mb-4">
            <a href="{{ $event->link }}" 
               target="_blank" 
               class="text-blue-600 underline hover:text-blue-800">
               Visit Event Page
            </a>
        </div>
    @endif

    {{-- Register Button
    <div class="mt-6">
        <a href="{{ route('registration.create', $event->id) }}" 
           class="bg-green-600 text-white px-5 py-2 rounded-xl hover:bg-green-700 transition">
           Register Now
        </a>
    </div> --}}
</div>