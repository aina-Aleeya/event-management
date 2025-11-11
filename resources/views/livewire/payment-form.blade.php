<div class="bg-white min-h-screen py-10">
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-3 gap-6 p-6">

        <!-- Sidebar: Event Info -->
        <div class="md:col-span-1 bg-gradient-to-b from-purple-100 to-white p-4 rounded-lg shadow-sm">
            <img src="{{ asset('storage/' . $registrations->first()->event->poster) }}"
                 alt="{{ $registrations->first()->event->title }}"
                 class="w-full h-56 object-cover rounded-lg shadow-md">

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
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Confirm Your Participants</h1>
                    <button wire:click="addMember"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition">
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
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">No</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">IC</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Club</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Category</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registrations as $index => $reg)
                                    <tr>
                                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2">{{ $reg->peserta->nama_penuh ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $reg->peserta->ic ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $reg->peserta->kelas ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $reg->kategori_nama ?? $reg->kategori ?? '-' }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <button wire:click="deleteParticipant({{ $reg->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow text-sm transition">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Total Amount & Payment Buttons -->
                <div class="flex justify-between items-center mt-6 border-t pt-4">
                    <div class="text-xl font-semibold text-gray-800">
                        Total: RM {{ number_format($totalAmount, 2) }}
                    </div>
                    <div>
                        <button wire:click="payNow"
                            class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg shadow transition">
                            Pay Now
                        </button>
                        <button wire:click="payLater"
                            class="ml-3 bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-2 rounded-lg shadow transition">
                            Pay Later
                        </button>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session()->has('success'))
                    <div class="mt-4 bg-green-100 text-green-800 p-3 rounded-lg text-center">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
