<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-6 space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ $event->title }}
                </h1>
                <p class="text-gray-600 mt-1">Event dashboard and analytics</p>
            </div>
            @if(auth()->user()->isOrganiser())
            <a href="{{ route('organiser.events.team.index', $event->id) }}"
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-50 to-green-100 border border-green-300 rounded-lg text-sm font-semibold text-green-700 hover:from-green-100 hover:to-green-200 hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Manage Team
            </a>
            @endif
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-5">
            {{-- Total Participants --}}
            <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-blue-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="blue" stroke="currentColor" viewBox="0 0 640 640">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M240 192C240 147.8 275.8 112 320 112C364.2 112 400 147.8 400 192C400 236.2 364.2 272 320 272C275.8 272 240 236.2 240 192zM448 192C448 121.3 390.7 64 320 64C249.3 64 192 121.3 192 192C192 262.7 249.3 320 320 320C390.7 320 448 262.7 448 192zM144 544C144 473.3 201.3 416 272 416L368 416C438.7 416 496 473.3 496 544L496 552C496 565.3 506.7 576 520 576C533.3 576 544 565.3 544 552L544 544C544 446.8 465.2 368 368 368L272 368C174.8 368 96 446.8 96 544L96 552C96 565.3 106.7 576 120 576C133.3 576 144 565.3 144 552L144 544z" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Clicks</p>
                <p class="text-3xl font-bold text-purple-600">{{ number_format($clickCount) }}</p>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Manage Certificates --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="white" viewBox="0 0 640 640">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="50"
                                    d="M271.2 56C265.1 49.8 256.2 47.3 247.8 49.6C239.4 51.9 232.9 58.4 230.8 66.8L215.5 127C214.4 131.4 209.9 134 205.6 132.7L145.8 115.9C137.4 113.5 128.4 115.9 122.3 122C116.2 128.1 113.8 137.1 116.2 145.5L133.1 205.3C134.3 209.6 131.7 214.1 127.4 215.2L67.1 230.5C58.7 232.6 52.1 239.2 49.8 247.6C47.5 256 50 264.9 56.2 271L100.7 314.3C103.9 317.4 103.9 322.6 100.7 325.8L56.3 369.1C50.1 375.2 47.6 384.1 49.9 392.5C52.2 400.9 58.8 407.4 67.2 409.6L127.4 424.9C131.8 426 134.4 430.5 133.1 434.8L116.2 494.5C113.8 502.9 116.2 511.9 122.3 518C128.4 524.1 137.4 526.5 145.8 524.1L205.6 507.2C209.9 506 214.4 508.6 215.5 512.9L230.8 573.1C232.9 581.5 239.5 588.1 247.9 590.4C256.3 592.7 265.2 590.2 271.3 584L314.6 539.5C317.7 536.3 322.9 536.3 326.1 539.5L369.3 584C375.4 590.2 384.3 592.7 392.7 590.4C401.1 588.1 407.6 581.5 409.8 573.1L425.1 513C426.2 508.6 430.7 506 435 507.3L494.8 524.2C503.2 526.6 512.2 524.2 518.3 518.1C524.4 512 526.8 503 524.4 494.6L507.5 434.8C506.3 430.5 508.9 426 513.2 424.9L573.4 409.6C581.8 407.5 588.4 400.9 590.7 392.5C593 384.1 590.5 375.1 584.3 369.1L539.8 325.8C536.6 322.7 536.6 317.5 539.8 314.3L584.3 271C590.5 264.9 593 256 590.7 247.6C588.4 239.2 581.8 232.7 573.4 230.5L513.2 215.2C508.8 214.1 506.2 209.6 507.5 205.3L524.4 145.5C526.8 137.1 524.4 128.1 518.3 122C512.2 115.9 503.2 113.5 494.8 115.9L435 132.8C430.7 134 426.2 131.4 425.1 127.1L409.8 66.8C407.7 58.4 401.1 51.8 392.7 49.5C384.3 47.2 375.4 49.7 369.3 55.9L326 100.5C322.9 103.7 317.7 103.7 314.5 100.5L271.2 56z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Certificate Management</h3>
                            <p class="text-sm text-gray-600">Manage certificates for participants</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.certificate.manage', $event->id) }}"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                        Manage
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Manage Groups --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
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
                        <tr
                            class="bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 uppercase text-xs border-b-2 border-gray-200">
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
                                                src="{{ asset('storage/' . $p->gambar) }}"
                                                alt="{{ $p->nama_penuh }}">
                                        @else
                                            <div
                                                class="w-10 h-10 mr-3 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border-2 border-blue-200">
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
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $p->pivot->category_name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                    {{ $p->pivot->unique_id ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($p->pivot->status_bayaran === 'complete')
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            Completed
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
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
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">No participants have registered yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($latestParticipants->count() > 0)
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-t border-gray-200 text-center">
                    <a href="{{ route('admin.participants', $event->id) }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-semibold inline-flex items-center gap-2">
                        Show all participants
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        {{-- RANKING SECTION --}}
        @include('livewire.admin.partials._event-rankings', ['event' => $event])
    </div>
</div>
