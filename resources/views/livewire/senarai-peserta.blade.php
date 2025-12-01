<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-grow container mx-auto px-4 py-8 sm:px-6 lg:px-8 max-w-7xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Registered Participant  List</h1>
            <p class="mt-2 text-sm text-gray-500">Manage participants and payment statuses for your upcoming events.</p>
        </div>

        <!-- Participant List Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-white space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-semibold tracking-wider text-indigo-600 uppercase mb-1 block">Selected
                            Event</span>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $registrations->first()->event->title ?? 'Event Tidak Dikenali' }}</h2>
                    </div>

                    <div class="flex flex-wrap gap-2">

                        @if($registrations->contains('status_bayaran', 'pending'))
                            <a href="{{ route('payment.form', ['event_id' => $eventId]) }}"
                                class="inline-flex items-center px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition shadow-md">
                                Process Pending Payments
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mt-4">
                    <!-- Search Box -->
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 3a8 8 0 011 15.938 7.958 7.958 0 01-1.062.062 8 8 0 010-16z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Search by name, MyKad, or club..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
                    </div>

                    <!-- Filter by Status -->
                    <div class="relative min-w-[160px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v9.272l3.25-1.818m-6.5 0L12 12.272V3m9 12.728a9 9 0 10-18 0 9 9 0 0018 0z" />
                            </svg>
                        </div>
                        <select
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm appearance-none transition">
                            <option value="all">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 text-center">
                                No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Full Name / MyKad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Club / Team</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registrations as $index => $reg)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-medium">
                                                    {{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-sm font-medium text-gray-900">{{ $reg->peserta->nama_penuh }}</span>
                                                        <span class="text-xs text-gray-500 font-mono mt-0.5">{{ $reg->peserta->ic }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $reg->peserta->kelas ?? 'Independent' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                        {{ $reg->kategori_nama }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $reg->status_bayaran === 'paid' ? 'bg-green-100 text-green-700' :
                            ($reg->status_bayaran === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-700') }}">
                                                        {{ ucfirst($reg->status_bayaran) }}
                                                    </span>
                                                </td>
                                            </tr>
                        @endforeach

                        @if($registrations->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    No participants found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Footer Section -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <a href="{{route('history')}}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
                    <- Back to History </a>
                        <div class="text-xs text-gray-400">
                            Showing {{ $registrations->count() }} of {{ $registrations->count() }} participants
                        </div>
            </div>
        </div>
    </div>
</div>