<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-3 gap-6 p-6">

        <!-- Sidebar: Event Info -->
        <div class="md:col-span-1 bg-gradient-to-b from-purple-50 to-white p-5 rounded-xl shadow-sm flex flex-col items-center">
            <img src="{{ asset('storage/' . $registrations->first()->event->poster) }}"
                 alt="{{ $registrations->first()->event->title }}"
                 class="w-full h-56 object-cover rounded-xl shadow-md">

            <h2 class="text-2xl font-bold mt-4 text-gray-800 text-center">{{ $registrations->first()->event->title }}</h2>
            <p class="text-sm text-gray-600 mt-1 text-center">{{ $registrations->first()->event->venue ?? 'No venue info' }}</p>

            <div class="border-t mt-6 pt-4 w-full text-center">
                <p class="font-semibold text-gray-800">Total Amount</p>
                <p class="text-purple-600 font-bold text-xl mt-1">
                    RM {{ number_format($totalAmount, 2) }}
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h1 class="text-2xl font-bold text-gray-800">Confirm Your Participants</h1>
                    <button wire:click="addMember"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">
                        + Add Member
                    </button>
                </div>
                
                @if($registrations->isEmpty())
                    <p class="text-gray-500">No participants yet.</p>
                @else
                    <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-purple-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">IC</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Club</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Category</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registrations as $index => $reg)
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2">{{ $reg->peserta->nama_penuh ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $reg->peserta->ic ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $reg->peserta->kelas ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $reg->kategori_nama ?? $reg->kategori ?? '-' }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <button wire:click="deleteParticipant({{ $reg->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow text-sm transition duration-200">
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
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 border-t pt-4 gap-3">
                    <div class="text-xl font-semibold text-gray-800">
                        Total: RM {{ number_format($totalAmount, 2) }}
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button wire:click="payNow"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">
                            Pay Now
                        </button>
                        <button wire:click="payLater"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-900 px-5 py-2 rounded-lg shadow transition duration-200">
                            Pay Later
                        </button>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session()->has('success'))
                    <div class="mt-4 bg-green-100 text-green-800 p-3 rounded-lg text-center shadow">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
