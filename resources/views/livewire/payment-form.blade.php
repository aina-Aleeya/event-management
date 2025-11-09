<div class="bg-white min-h-screen py-10">
    <div
        class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-3 gap-6 p-6">

        <!-- Sidebar: Event Info -->
        <div class="md:col-span-1 bg-gradient-to-b from-purple-100 to-white p-4 rounded-lg shadow-sm">
            <img src="{{ asset('storage/' . $registrations->first()->event->poster) }}"
                alt="{{ $registrations->first()->event->title }}" class="w-full h-56 object-cover rounded-lg shadow-md">

            <h2 class="text-xl font-bold mt-4 text-gray-800">{{ $registrations->first()->event->title }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ $registrations->first()->event->venue ?? 'No venue info' }}</p>

            <div class="border-t mt-4 pt-3">
                <p class="font-semibold text-gray-800">Total Amount</p>
                <p class="text-purple-600 font-bold text-lg">
                    RM {{ number_format($totalAmount, 2) }}
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2 flex flex-col justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-3">Confirm Your Selection</h1>
                <p class="text-gray-600 mb-6">
                    Please check your registration details before proceeding with payment.
                </p>

                <!-- Participants List -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6 shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-2">Participants ({{ $registrations->count() }})</h3>
                    <ul class="text-gray-700 text-sm space-y-1">
                        @foreach ($registrations as $reg)
                            <li>• {{ $reg->peserta->nama_penuh }} </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Total Row -->
                <div class="flex items-center justify-between border-t border-gray-200 pt-4 mt-4">
                    <div class="text-gray-700 text-base font-medium">
                        Total Amount
                    </div>
                    <div class="text-xl font-bold text-purple-700">
                        RM {{ number_format($totalAmount, 2) }}
                    </div>
                </div>

                <!-- Payment Buttons -->
                <div class="text-right mt-6">
                    <button wire:click="payNow"
                        class="bg-purple-300 hover:bg-sky-300 text-gray-900 font-semibold px-4 py-2 rounded-lg shadow transition">
                        Pay Now
                    </button>

                    <button wire:click="payLater"
                        class="ml-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold px-4 py-2 rounded-lg shadow transition">
                        Pay Later
                    </button>
                </div>
            </div>

            <!-- Success Message -->
            @if (session()->has('success'))
                <div class="mt-6 bg-green-100 text-green-800 p-3 rounded-lg text-center">
                    {{ session('success') }}
                </div>
            @endif
        </div>

    </div>
</div>