<div class="bg-white min-h-screen py-10">
    <div
        class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-3 gap-6 p-6">

        <!-- Event Info -->
        <div
            class="md:col-span-1 h-fit bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <!-- Poster Image -->
            <div class="relative h-64 w-full overflow-hidden group">
                <img src="{{ !empty($registrations->first()->event->posters) && isset($registrations->first()->event->posters[0]) ? asset('storage/' . $registrations->first()->event->posters[0]) : asset('img/sample-event.jpg') }}"
                    alt="{{ $registrations->first()->event->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4 text-white">
                    <span class="bg-rose-600 text-white text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wide">
                        Upcoming
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 leading-tight mb-4">
                    {{ $registrations->first()->event->title }}
                </h2>

                <div class="space-y-3 mb-6">
                    <!-- Venue Info -->
                    <div class="flex items-start text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-5 h-5 text-rose-500 mr-3"></i>
                        <span>{{ $registrations->first()->event->venue ?? 'No venue info' }}</span>
                    </div>
                    <!-- Date Info -->
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-alt w-5 h-5 text-rose-500 mr-3"></i>
                        <span>{{ \Carbon\Carbon::parse($registrations->first()->event->start_date)->format('d-m-Y') ?? 'No date info' }}</span>
                    </div>

                </div>

                <!-- Payment Info -->
                <div class="bg-rose-50 rounded-xl p-4 border border-rose-100">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-medium text-rose-800">Total Payable</p>
                        <i class="fas fa-info-circle w-4 h-4 text-rose-400"></i>
                    </div>
                    <p class="text-2xl font-bold text-rose-600">
                        RM {{ number_format($totalAmount, 2) }}
                    </p>
                    <p class="text-xs text-rose-500 mt-1">
                        Includes all taxes & fees
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Section: Participants Table + Add Member Button -->
        <div class="col-span-2 md:col-span-2 bg-gray-100 rounded-xl shadow-lg overflow-hidden p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Confirm Your Participants</h1>
                <button wire:click="addMember"
                    class="bg-gray-900 hover:bg-gray-800 text-white px-2 py-1 rounded-lg shadow-md transition-all active:scale-95 text-sm font-medium">
                    + Add Member
                </button>
            </div>

            @if($registrations->isEmpty())
                <p class="text-gray-600">No participants yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 rounded-lg border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Name & IC</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Club</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registrations as $index => $reg)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-semibold text-gray-900">{{ $reg->peserta->nama_penuh ?? '-' }}</span>
                                            <span
                                                class="text-xs text-gray-500 font-mono mt-0.5">{{ $reg->peserta->ic ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $reg->peserta->kelas ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $reg->kategori_nama ?? $reg->kategori ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <button wire:click="deleteParticipant({{ $reg->id }})"
                                            class="text-gray-400 hover:text-red-600 transition-colors p-2 hover:bg-red-50 rounded-full"
                                            title="Remove participant">
                                            <i class="fas fa-trash-alt w-5 h-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Total Amount & Payment Buttons Section on the Right -->
            <div class="flex justify-between items-center mt-8">
                <div class="flex flex-col items-start text-center md:text-left">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Grand Total</span>
                    <div class="text-xl font-bold text-gray-900">
                        RM {{ number_format($totalAmount, 2) }}
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto ml-6">
                    <!-- Pay Later Button -->
                    <button wire:click="payLater"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all text-sm">
                        <i class="fas fa-clock w-4 h-4"></i>
                        Pay Later
                    </button>

                    <!-- Pay Now Button -->
                    <button wire:click="payNow"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-6 py-2 rounded-lg bg-rose-600 text-white font-medium shadow-lg shadow-rose-200 hover:bg-rose-700 hover:shadow-rose-300 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transition-all text-sm">
                        <i class="fas fa-credit-card w-4 h-4"></i>
                        Pay Now
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>