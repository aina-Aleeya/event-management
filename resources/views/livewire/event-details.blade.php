<div class="max-w-4xl mx-auto p-8 bg-gradient-to-br from-white to-blue-50 shadow-xl rounded-2xl mt-10 space-y-8">
    {{-- Event Poster --}}
    @if ($event->poster)
        <div class="relative overflow-hidden rounded-2xl shadow-md">
            <img 
                src="{{ asset('storage/'.$event->poster) }}" 
                alt="Event Poster"
                class="w-full max-h-[450px] object-cover transition-transform duration-500">
        </div>
    @endif

    {{-- Title --}}
    <div class="text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">
            {{ $event->title }}
        </h1>
        <p class="text-blue-600 font-medium uppercase tracking-wide">
            {{ $event->event_type }}
        </p>
    </div>

    {{-- Event Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/80 p-6 rounded-xl shadow-sm">
        <div>
            <p class="text-gray-700 mb-1"><strong>Date:</strong></p>
            <p class="text-gray-800">
                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} – 
                {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
            </p>
        </div>
        <div>
            <p class="text-gray-700 mb-1"><strong>Time:</strong></p>
            <p class="text-gray-800">
                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} – 
                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
            </p>
        </div>
        <div>
            <p class="text-gray-700 mb-1"><strong>Venue:</strong></p>
            <p class="text-gray-800">{{ $event->venue }}</p>
        </div>
        <div>
            <p class="text-gray-700 mb-1"><strong>City:</strong></p>
            <p class="text-gray-800">{{ $event->city }}</p>
        </div>
        @if ($event->entry_fee)
        <div>
            <p class="text-gray-700 mb-1"><strong>Entry Fee:</strong></p>
            <p class="text-gray-800">RM {{ number_format($event->entry_fee, 2) }}</p>
        </div>
        @endif
        @if ($event->max_participants)
        <div>
            <p class="text-gray-700 mb-1"><strong>Max Participants:</strong></p>
            <p class="text-gray-800">{{ $event->max_participants }}</p>
        </div>
        @endif
    </div>

    {{-- Description --}}
    <div class="prose max-w-none text-gray-700 leading-relaxed">
        {!! $event->description !!}
    </div>

    {{-- Categories --}}
@if (!empty($event->categories))
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Categories</h3>
        <div class="flex flex-wrap gap-2">
            @foreach (explode(',', $event->categories) as $cat)
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                    {{ trim($cat) }}
                </span>
            @endforeach
        </div>
    </div>
@endif


    {{-- QR Code --}}
    @if ($event->qr_code)
        <div class="text-center mt-6" x-data="{ showQR: false }">
            <button 
                @click="showQR = !showQR" 
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                <span x-show="!showQR">Show QR Code</span>
                <span x-show="showQR">Hide QR Code</span>
            </button>

            <div x-show="showQR" x-transition class="mt-4">
                <img src="{{ asset('storage/'.$event->qr_code) }}" 
                     alt="QR Code" 
                     class="w-44 mx-auto rounded-lg shadow-md">
            </div>
        </div>
    @endif

    {{-- Contact Info --}}
    <div class="bg-white/70 p-6 rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Contact Information</h3>
        <p class="text-gray-700">
            📧 {{ $event->contact_email }}<br>
            📞 {{ $event->contact_phone }}
        </p>
    </div>

    {{-- Register Button --}}
<a href="#" 
   class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl shadow-lg hover:opacity-90 transition font-semibold">
   Register Now
</a>

</div>
