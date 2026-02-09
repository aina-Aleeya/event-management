<div class="min-h-screen bg-gradient-to-b from-purple-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Page Header --}}
        <div class="text-center mb-10 mt-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl mb-4 shadow-lg">
                <i class="fa-solid fa-credit-card text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                Payment
            </h1>
            <p class="text-gray-600 text-lg">
                Review your participants and complete your payment
            </p>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Section: Event Info Card (Always Visible) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden lg:sticky lg:top-6">
                    
                    {{-- Event Poster --}}
                    <div class="relative h-64 w-full overflow-hidden group">
                        <img src="{{ !empty($registrations->first()->event->posters) && isset($registrations->first()->event->posters[0]) ? asset('storage/' . $registrations->first()->event->posters[0]) : asset('img/sample-event.jpg') }}"
                            alt="{{ $registrations->first()->event->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold rounded-full shadow-lg">
                                <i class="fa-solid fa-calendar-check"></i>
                                Upcoming
                            </span>
                        </div>
                        
                        <div class="absolute bottom-4 left-4 right-4">
                            <h2 class="text-white text-xl font-bold drop-shadow-lg">
                                {{ $registrations->first()->event->title }}
                            </h2>
                        </div>
                    </div>

                    {{-- Event Details --}}
                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-location-dot text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Venue</p>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ $registrations->first()->event->venue ?? 'No venue info' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-calendar text-pink-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Date</p>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ \Carbon\Carbon::parse($registrations->first()->event->start_date)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 my-4"></div>

                        <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl p-6 text-white shadow-xl">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold uppercase tracking-wide opacity-90">Total Payable</p>
                                <i class="fa-solid fa-info-circle opacity-75"></i>
                            </div>
                            <p class="text-4xl font-bold mb-1">
                                RM {{ number_format($totalAmount, 2) }}
                            </p>
                            <p class="text-xs opacity-80">
                                Includes all taxes & fees
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Section: Dynamic Content --}}
            <div class="lg:col-span-2">
                @if($showUploadForm)
                    @include('livewire.user.payment-receipt-upload')
                @else
                    @include('livewire.user.payment-participants-list')
                @endif
            </div>

        </div>
    </div>
</div>