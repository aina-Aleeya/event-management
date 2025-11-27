<div class="bg-white min-h-screen py-10">
    <div
        class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-3 gap-6 p-6">

        <!-- Sidebar: Event Info -->
        <div
            class="md:col-span-1 bg-gradient-to-b from-red-50 to-white p-5 rounded-xl shadow-sm flex flex-col items-center">
            @php
                $event = $registrations->first()->event;
                $firstPoster = is_array($event->posters) && count($event->posters) > 0 ? $event->posters[0] : null;
            @endphp

            <img src="{{ $firstPoster ? asset('storage/' . $firstPoster) : asset('img/sample-event.jpg') }}"
                alt="Event Poster" class="w-full h-full object-cover"
            alt="{{ $registrations->first()->event->title }}"
            class="w-full h-56 object-cover rounded-lg shadow-md"/>

            <h2 class="text-2xl font-bold mt-4 text-gray-800 text-center">{{ $registrations->first()->event->title }}
            </h2>
            <p class="text-sm text-gray-600 mt-1 text-center">
                {{ $registrations->first()->event->venue ?? 'No venue info' }}</p>

            <div class="border-t mt-6 pt-4 w-full text-center">
                <p class="font-semibold text-gray-800">Total Amount</p>
                <p class="text-red-300 font-bold text-xl mt-1">
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
                        class="bg-red-300 hover:bg-red-200 text-gray-900 px-3 py-1 rounded-lg shadow transition">
                        + Add Member
                    </button>
                </div>

                @if ($registrations->isEmpty())
                    <p class="text-gray-600">No participants yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 w-12">No</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Name</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">IC</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Club</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Category</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($registrations as $index => $reg)
                                    <tr>
                                        <td class="px-4 py-2 text-center w-12">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-center">{{ $reg->peserta->nama_penuh ?? '-' }}</td>
                                        <td class="px-4 py-2 text-center">{{ $reg->peserta->ic ?? '-' }}</td>
                                        <td class="px-4 py-2 text-center">{{ $reg->peserta->kelas ?? '-' }}</td>
                                        <td class="px-4 py-2 text-center">{{ $reg->kategori_nama ?? $reg->kategori ?? '-' }}</td>
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
                            class="bg-red-200 hover:bg-red-300 text-gray-900 px-3 py-1 rounded-lg shadow transition">
                            Pay Now
                        </button>
                        <button wire:click="payLater"
                            class="ml-3 bg-gray-200 hover:bg-gray-300 text-gray-900 px-3 py-1 rounded-lg shadow transition">
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

                @if ($showModal)
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 3000)">
                        <template x-if="show">
                            <div class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-sm z-50">
                                <!-- Modal Content -->
                                <div class="mt-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-80 text-center pointer-events-auto">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Please Login</h2>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                                        Login is required to view the registered participants list.<br>
                                        <strong>Click 'Forget Your Password' to continue login.</strong>
                                    </p>
                                    <div class="flex justify-center gap-3">
                                        <button wire:click="closeModal"
                                                class="bg-red-200 hover:bg-red-300 text-gray-900 px-4 py-2 rounded-lg transition">
                                            Stay as Guest
                                        </button>
                                        <a href="{{ route('login') }}"
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                                            Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
