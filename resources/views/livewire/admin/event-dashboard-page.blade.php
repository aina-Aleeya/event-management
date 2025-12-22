<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-6 space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ $event->title }}
                </h1>
                <p class="text-gray-600 mt-1">Event dashboard and analytics</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-5">
            {{-- Total Participants --}}
            <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-blue-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Participants</p>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($totalParticipants ?? 0) }}</p>
            </div>

            {{-- Payments Completed --}}
            <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-green-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Completed Payments</p>
                <p class="text-3xl font-bold text-green-600">{{ number_format($completedPayments) }}</p>
            </div>

            {{-- Pending Payments --}}
            <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-orange-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Pending Payments</p>
                <p class="text-3xl font-bold text-orange-600">{{ number_format($pendingPayments ?? 0) }}</p>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-indigo-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Revenue</p>
                <p class="text-3xl font-bold text-indigo-600">RM {{ number_format($totalRevenue ?? 0) }}</p>
            </div>

            {{-- Total Clicks --}}
            <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-purple-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Clicks</p>
                <p class="text-3xl font-bold text-purple-600">{{ number_format($clickCount) }}</p>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- View All Participants --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">View Participants</h3>
                            <p class="text-sm text-gray-600">See all registered participants</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.participants', $event->id) }}"
                       class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Manage Groups --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Manage Groups</h3>
                            <p class="text-sm text-gray-600">Organize participants into groups</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.event.grouping', ['event' => $event->id]) }}"
                       class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                        Manage
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- PARTICIPANT OVERVIEW --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Recent Participants</h2>
                        <p class="text-sm text-gray-600 mt-1">Latest registrations</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 uppercase text-xs border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left font-semibold">Participant</th>
                            <th class="px-6 py-4 text-left font-semibold">Category</th>
                            <th class="px-6 py-4 text-left font-semibold">Unique ID</th>
                            <th class="px-6 py-4 text-left font-semibold">Payment</th>
                            <th class="px-6 py-4 text-left font-semibold">Date</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($latestParticipants as $p)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.participant.view', $p->id) }}"
                                       class="flex items-center text-sm hover:opacity-80 transition">
                                        @if ($p->gambar)
                                            <img class="w-10 h-10 mr-3 rounded-full object-cover border-2 border-gray-200"
                                                 src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_penuh }}">
                                        @else
                                            <div class="w-10 h-10 mr-3 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border-2 border-blue-200">
                                                {{ strtoupper(substr($p->nama_penuh, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $p->nama_penuh }}</p>
                                            <p class="text-xs text-gray-500">Registered Participant</p>
                                        </div>
                                    </a>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $p->pivot->category_name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                    {{ $p->pivot->unique_id ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($p->pivot->status_bayaran === 'complete')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            Completed
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $p->pivot->created_at->format('d/m/Y') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium">No participants have registered yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($latestParticipants->count() > 0)
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-t border-gray-200 text-center">
                    <a href="{{ route('admin.participants', $event->id) }}"
                       class="text-blue-600 hover:text-blue-800 text-sm font-semibold inline-flex items-center gap-2">
                        Show all participants
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        {{-- RANKING SECTION --}}
        @include('livewire.admin.partials._event-rankings', ['event' => $event])
    </div>
</div>
