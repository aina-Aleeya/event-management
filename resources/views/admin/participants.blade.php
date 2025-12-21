<x-layouts.app.admin>
    <div class="max-w-7xl mx-auto px-6 py-6">

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <!-- Header with Title + Export Buttons -->
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Participant Overview</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $participants->count() }} registered participants</p>
                    </div>

                    <div class="flex gap-3">
                        <!-- Excel Export Button -->
                        <a href="{{ route('admin.event.participants.export', $event->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-md text-sm font-medium text-green-700 hover:bg-green-100 transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>
                            Export Excel
                        </a>

                        <!-- PDF Export Button -->
                        <a href="{{ route('admin.event.participants.pdf', $event->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-md text-sm font-medium text-red-700 hover:bg-red-100 transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
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
                                    <a href="{{ route('admin.participant.view', $p->id) }}"
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
                                            <p class="text-xs text-gray-600">Registered Participant</p>
                                        </div>
                                    </a>
                                </td>

                                <!-- CATEGORY -->
                                <td class="px-4 py-3 text-sm">{{ $p->pivot->kategori_nama }}</td>

                                <!-- UNIQUE ID -->
                                <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ $p->pivot->unique_id }}</td>

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

    </div>
</x-layouts.app.admin>