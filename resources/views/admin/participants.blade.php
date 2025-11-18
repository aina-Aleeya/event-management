<x-layouts.app.admin>
    {{-- Page header --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Participants — {{ $event->title }}
            </h2>

            {{-- Navigation Bar --}}
            <nav class="flex flex-wrap gap-2">
                <a href="" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                    Dashboard
                </a>
                <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Events
                </a>
                <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Participants
                </a>
                {{-- <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Reports
                </a> --}}
            </nav>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-xl font-semibold mb-4">Participant List</h1>

            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Participant</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Unique ID</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y">

                        @forelse ($participants as $p)
                            <tr class="text-gray-700">
                                <!-- NAME + AVATAR -->
                                <td class="px-4 py-3">
                                    <a href="{{ route('organiser.participant.view', $p->id) }}"
                                        class="flex items-center text-sm hover:opacity-80 transition">


                                        @if($p->gambar)
                                            <img class="w-9 h-9 mr-3 rounded-full object-cover"
                                                src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_penuh }}">
                                        @else

                                            <div
                                                class="relative w-9 h-9 mr-3 rounded-full bg-purple-200 flex items-center justify-center text-purple-700 font-bold">
                                                {{ strtoupper(substr($p->nama_penuh, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-semibold">{{ $p->nama_penuh }}</p>
                                            <p class="text-xs text-gray-600">
                                                Registered Participant
                                            </p>
                                        </div>
                                    </a>
                                </td>


                                <!-- CATEGORY -->
                                <td class="px-4 py-3 text-sm">
                                    {{ $p->pivot->kategori_nama }}
                                </td>

                                <!-- UNIQUE ID -->
                                <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                    {{ $p->pivot->unique_id }}
                                </td>

                                <!-- PAYMENT STATUS -->
                                <td class="px-4 py-3 text-xs">
                                    @if ($p->pivot->status_bayaran === 'complete')
                                        <span class="px-2 py-1 text-green-700 bg-green-100 rounded-full font-semibold">
                                            Completed
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-orange-700 bg-orange-100 rounded-full font-semibold">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- DATE -->
                                <td class="px-4 py-3 text-sm">
                                    {{ $p->pivot->created_at ? $p->pivot->created_at->format('d/m/Y') : '-' }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    No participants have registered yet.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('organiser.events.report', $event->id) }}"
                class="px-4 py-2 bg-red-300 text-black rounded-lg hover:bg-red-500 transition">
                Generate Report
            </a>
        </div>

    </div>
</x-layouts.app.admin>