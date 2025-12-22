<x-layouts.app.admin>
    <div class="max-w-7xl mx-auto px-6 py-6">

        <!-- Page Header -->
        <div class="mb-6 mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }} - Rankings</h1>
                    <p class="text-sm text-gray-600 mt-1">View participant rankings by average score</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Export PDF Button -->
                    <a href="{{ route('admin.ranking.export.pdf', ['event' => $event->id, 'category' => $selectedCategory]) }}" 
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-50 to-red-100 border border-red-300 rounded-lg text-sm font-semibold text-red-700 hover:from-red-100 hover:to-red-200 hover:shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export PDF
                    </a>

                    <!-- Export Sheet Button -->
                    <a href="{{ route('admin.ranking.export.sheet', ['event' => $event->id, 'category' => $selectedCategory]) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-50 to-green-100 border border-green-300 rounded-lg text-sm font-semibold text-green-700 hover:from-green-100 hover:to-green-200 hover:shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Export Sheet
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Filter Card -->
        @if($categories->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 mb-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900">Filter by Category</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.ranking.show', $event->id) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !$selectedCategory ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All Categories
                        </a>
                        
                        @foreach($categories as $category)
                            <a href="{{ route('admin.ranking.show', ['event' => $event->id, 'category' => $category]) }}"
                               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $selectedCategory === $category ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Rankings Table Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg shadow-md">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                @if($selectedCategory)
                                    {{ $selectedCategory }} Ranking
                                @else
                                    Overall Ranking
                                @endif
                            </h2>
                            <p class="text-sm text-gray-500 mt-0.5">Sorted by average score</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($scores->count() > 0)
                <!-- Podium Section for Top 3 -->
                <div class="px-6 py-8 bg-gradient-to-br from-amber-50 via-yellow-50 to-orange-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 text-center">🏆 Top Performers 🏆</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
                        @foreach($scores->take(3) as $topScore)
                            <div class="bg-white rounded-xl p-5 border-2 {{ $topScore->rank === 1 ? 'border-amber-400 shadow-xl' : ($topScore->rank === 2 ? 'border-gray-400 shadow-lg' : 'border-orange-400 shadow-lg') }} transform transition-all hover:scale-105">
                                <div class="text-center mb-4">
                                    <div class="text-5xl mb-2">
                                        @if($topScore->rank === 1)
                                            🥇
                                        @elseif($topScore->rank === 2)
                                            🥈
                                        @else
                                            🥉
                                        @endif
                                    </div>
                                    <div class="text-2xl font-bold {{ $topScore->rank === 1 ? 'text-amber-600' : ($topScore->rank === 2 ? 'text-gray-600' : 'text-orange-600') }}">
                                        {{ $topScore->rank }}{{ $topScore->rank === 1 ? 'st' : ($topScore->rank === 2 ? 'nd' : 'rd') }} Place
                                    </div>
                                </div>
                                <div class="text-center border-t border-gray-200 pt-4">
                                    <p class="font-bold text-gray-900 text-lg mb-1">{{ $topScore->peserta->nama_penuh }}</p>
                                    <div class="flex items-center justify-center gap-2 mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $topScore->category }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $topScore->group->name }}
                                        </span>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <p class="text-sm text-gray-600 mb-1">Average Score</p>
                                        <p class="text-3xl font-bold text-gray-900">{{ number_format($topScore->average, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Full Rankings Table -->
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-200 bg-gray-50">
                                <th class="px-6 py-4">Rank</th>
                                <th class="px-6 py-4">Participant</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4">Group</th>
                                <th class="px-6 py-4 text-center">Round 1</th>
                                <th class="px-6 py-4 text-center">Round 2</th>
                                <th class="px-6 py-4 text-center">Round 3</th>
                                <th class="px-6 py-4 text-center">Average</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($scores as $score)
                                <tr class="hover:bg-gray-50 transition-colors {{ $score->rank <= 3 ? 'bg-amber-50/30' : '' }}">
                                    <!-- Rank -->
                                    <td class="px-6 py-4">
                                        @if($score->rank === 1)
                                            <div class="flex items-center gap-2">
                                                <span class="text-2xl">🥇</span>
                                                <span class="text-lg font-bold text-amber-600">1st</span>
                                            </div>
                                        @elseif($score->rank === 2)
                                            <div class="flex items-center gap-2">
                                                <span class="text-2xl">🥈</span>
                                                <span class="text-lg font-bold text-gray-500">2nd</span>
                                            </div>
                                        @elseif($score->rank === 3)
                                            <div class="flex items-center gap-2">
                                                <span class="text-2xl">🥉</span>
                                                <span class="text-lg font-bold text-orange-600">3rd</span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold border border-gray-200">
                                                {{ $score->rank }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Participant -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $score->peserta->nama_penuh }}</div>
                                    </td>

                                    <!-- Category -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $score->category }}
                                        </span>
                                    </td>

                                    <!-- Group -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            {{ $score->group->name }}
                                        </span>
                                    </td>

                                    <!-- Round Scores -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold bg-gray-100 text-gray-900 border border-gray-200">
                                            {{ number_format($score->round1, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold {{ $score->round2 ? 'bg-gray-100 text-gray-900 border border-gray-200' : 'bg-gray-50 text-gray-400 border border-gray-100' }}">
                                            {{ $score->round2 ? number_format($score->round2, 2) : '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold {{ $score->round3 ? 'bg-gray-100 text-gray-900 border border-gray-200' : 'bg-gray-50 text-gray-400 border border-gray-100' }}">
                                            {{ $score->round3 ? number_format($score->round3, 2) : '—' }}
                                        </span>
                                    </td>

                                    <!-- Average Score -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-bold bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md">
                                            {{ number_format($score->average, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="p-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-20 h-20 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">No Scores Submitted Yet</h3>
                        <p class="text-gray-500 mb-4">Rankings will appear once participants submit their scores for this event.</p>
                        <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium border border-blue-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Waiting for score submissions
                        </div>
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-semibold text-gray-900">{{ $scores->count() }}</span> ranked participants
                    </p>
                    <div class="text-sm text-gray-500">
                        Last updated: {{ now()->format('d M Y, h:i A') }}
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-layouts.app.admin>