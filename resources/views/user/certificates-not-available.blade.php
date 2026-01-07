<x-layouts.app.admin>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 py-12">
        <div class="max-w-2xl mx-auto px-6">
            
            <div class="text-center">
                <!-- Icon -->
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full mb-6">
                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-4">Certificates Not Available</h1>
                
                @if($event->certificates_available_from && now()->lt($event->certificates_available_from))
                    <p class="text-lg text-gray-600 mb-2">
                        Certificates for <span class="font-semibold">{{ $event->title }}</span> will be available starting:
                    </p>
                    <p class="text-2xl font-bold text-purple-600 mb-6">
                        {{ $event->certificates_available_from->format('F d, Y - g:i A') }}
                    </p>
                    <p class="text-gray-600">
                        Please check back after this date to download your certificate.
                    </p>
                @else
                    <p class="text-lg text-gray-600 mb-6">
                        Certificates for <span class="font-semibold">{{ $event->title }}</span> are currently not available.
                    </p>
                    <p class="text-gray-600">
                        Please contact the event organizer for more information.
                    </p>
                @endif

                <!-- Contact Button -->
                @if($event->contact_email)
                    <div class="mt-8">
                        <a href="mailto:{{ $event->contact_email }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact Organizer
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app.admin>