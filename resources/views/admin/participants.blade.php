<x-layouts.app.admin>
    <div class="max-w-7xl mx-auto px-6 py-6">

        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Participant Management</h1>
                    <p class="text-sm text-gray-600 mt-1">View and manage all registered participants for this event</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Excel Export Button -->
                    <a href="{{ route('admin.event.participants.export', $event->id) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-50 to-green-100 border border-green-300 rounded-lg text-sm font-semibold text-green-700 hover:from-green-100 hover:to-green-200 hover:shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>

                    <!-- PDF Export Button -->
                    <a href="{{ route('admin.event.participants.pdf', $event->id) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-50 to-red-100 border border-red-300 rounded-lg text-sm font-semibold text-red-700 hover:from-red-100 hover:to-red-200 hover:shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Export PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Participants -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">Total Participants</p>
                        <p class="text-3xl font-bold text-blue-900">{{ $participants->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Payments -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">Paid</p>
                        <p class="text-3xl font-bold text-green-900">{{ $participants->where('pivot.status_bayaran', 'complete')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border border-orange-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-600 mb-1">Pending</p>
                        <p class="text-3xl font-bold text-orange-900">{{ $participants->where('pivot.status_bayaran', '!=', 'complete')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 mb-1">Categories</p>
                        <p class="text-3xl font-bold text-purple-900">{{ $participants->pluck('pivot.kategori_nama')->unique()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">

            <!-- Search and Filter Bar -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search Box -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="searchInput" 
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" 
                                placeholder="Search by name, unique ID, or category..." />
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-center gap-2">
                        <button data-filter="all" class="filter-btn px-4 py-2 text-sm font-medium rounded-lg border transition-all bg-blue-600 text-white border-blue-600">
                            All
                        </button>
                        <button data-filter="complete" class="filter-btn px-4 py-2 text-sm font-medium rounded-lg border transition-all bg-white text-gray-700 border-gray-300 hover:bg-gray-50">
                            Paid
                        </button>
                        <button data-filter="pending" class="filter-btn px-4 py-2 text-sm font-medium rounded-lg border transition-all bg-white text-gray-700 border-gray-300 hover:bg-gray-50">
                            Pending
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-200 bg-gray-50">
                            <th class="px-6 py-4">
                                <div class="flex items-center">
                                    Participant
                                </div>
                            </th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Unique ID</th>
                            <th class="px-6 py-4">Payment Status</th>
                            <th class="px-6 py-4">Registration Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="participantsTable" class="bg-white divide-y divide-gray-100">
                        @forelse ($participants as $p)
                            <tr class="participant-row hover:bg-gray-50 transition-colors" 
                                data-name="{{ strtolower($p->nama_penuh) }}" 
                                data-category="{{ strtolower($p->pivot->kategori_nama) }}"
                                data-unique-id="{{ strtolower($p->pivot->unique_id) }}"
                                data-status="{{ $p->pivot->status_bayaran }}">
                                
                                <!-- PARTICIPANT INFO -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($p->gambar)
                                            <img class="w-11 h-11 rounded-full object-cover ring-2 ring-gray-200" 
                                                src="{{ asset('storage/' . $p->gambar) }}" 
                                                alt="{{ $p->nama_penuh }}">
                                        @else
                                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold text-lg ring-2 ring-purple-200">
                                                {{ strtoupper(substr($p->nama_penuh, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <p class="text-sm font-semibold text-gray-900">{{ $p->nama_penuh }}</p>
                                            <p class="text-xs text-gray-500">{{ $p->email ?? 'Registered Participant' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- CATEGORY -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $p->pivot->kategori_nama }}
                                    </span>
                                </td>

                                <!-- UNIQUE ID -->
                                <td class="px-6 py-4">
                                    <code class="px-2.5 py-1 text-xs font-mono font-semibold text-gray-700 bg-gray-100 rounded border border-gray-300">
                                        {{ $p->pivot->unique_id }}
                                    </code>
                                </td>

                                <!-- PAYMENT STATUS -->
                                <td class="px-6 py-4">
                                    @if ($p->pivot->status_bayaran === 'complete')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- DATE -->
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $p->pivot->created_at ? $p->pivot->created_at->format('d M Y') : '-' }}
                                    </div>
                                </td>

                                <!-- ACTIONS -->
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.participant.view', $p->id) }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-gray-500 font-medium">No participants registered yet</p>
                                        <p class="text-gray-400 text-sm mt-1">Participants will appear here once they register for this event</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer with Results Count -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Showing <span id="visibleCount" class="font-semibold text-gray-900">{{ $participants->count() }}</span> 
                        of <span class="font-semibold text-gray-900">{{ $participants->count() }}</span> participants
                    </p>
                    
                </div>
            </div>

        </div>

    </div>

    <!-- JavaScript for Search and Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const participantRows = document.querySelectorAll('.participant-row');
            const visibleCountSpan = document.getElementById('visibleCount');
            
            let currentFilter = 'all';

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                filterParticipants(searchTerm, currentFilter);
            });

            // Filter functionality
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                    });
                    this.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    this.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

                    currentFilter = this.dataset.filter;
                    const searchTerm = searchInput.value.toLowerCase();
                    filterParticipants(searchTerm, currentFilter);
                });
            });

            function filterParticipants(searchTerm, filter) {
                let visibleCount = 0;

                participantRows.forEach(row => {
                    const name = row.dataset.name;
                    const category = row.dataset.category;
                    const uniqueId = row.dataset.uniqueId;
                    const status = row.dataset.status;

                    const matchesSearch = name.includes(searchTerm) || 
                                        category.includes(searchTerm) || 
                                        uniqueId.includes(searchTerm);
                    
                    const matchesFilter = filter === 'all' || 
                                        (filter === 'complete' && status === 'complete') ||
                                        (filter === 'pending' && status !== 'complete');

                    if (matchesSearch && matchesFilter) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                visibleCountSpan.textContent = visibleCount;
            }
        });
    </script>
</x-layouts.app.admin>