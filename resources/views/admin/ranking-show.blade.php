<x-layouts.app.admin>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $event->title }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Rankings by Average Score</p>
                </div>
            <!-- Export Buttons -->
             <div class="flex items-center gap-2">
                <a href="{{ route('admin.ranking.export.pdf', ['event' => $event->id, 'category' => $selectedCategory]) }}" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 rounded-md text-sm font-medium text-red-700 hover:bg-red-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </a>
            <a href="{{ route('admin.ranking.export.sheet', ['event' => $event->id, 'category' => $selectedCategory]) }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-md text-sm font-medium text-green-700 hover:bg-green-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        Export Sheet
    </a>
</div>
</div>

            <!-- Category Filter -->
            @if($categories->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
                    <label class="text-sm font-medium text-gray-700 mb-3 block">Filter by Category</label>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.ranking.show', $event->id) }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ !$selectedCategory ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All Categories
                        </a>
                        
                        @foreach($categories as $category)
                            <a href="{{ route('admin.ranking.show', ['event' => $event->id, 'category' => $category]) }}"
                               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ $selectedCategory === $category ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Rankings Table -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @if($selectedCategory)
                            {{ $selectedCategory }} Ranking
                        @else
                            Overall Ranking
                        @endif
                    </h2>
                </div>

                @if($scores->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Round 1</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Round 2</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Round 3</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($scores as $score)
                                    <tr class="hover:bg-gray-50 transition-colors {{ $score->rank <= 3 ? 'bg-blue-50/30' : '' }}">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($score->rank === 1)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-2xl">🥇</span>
                                                    <span class="text-base font-semibold text-amber-600">1st</span>
                                                </div>
                                            @elseif($score->rank === 2)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-2xl">🥈</span>
                                                    <span class="text-base font-semibold text-gray-500">2nd</span>
                                                </div>
                                            @elseif($score->rank === 3)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-2xl">🥉</span>
                                                    <span class="text-base font-semibold text-orange-600">3rd</span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                                    {{ $score->rank }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $score->peserta->nama_penuh }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $score->category }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $score->group->name }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-sm font-medium text-gray-900">{{ number_format($score->round1, 2) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-sm font-medium text-gray-900">{{ $score->round2 ? number_format($score->round2, 2) : '—' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-sm font-medium text-gray-900">{{ $score->round3 ? number_format($score->round3, 2) : '—' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-gray-900 text-white">
                                                {{ number_format($score->average, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-900 text-sm font-medium">No scores submitted yet</p>
                        <p class="text-gray-500 text-sm mt-1">Rankings will appear once participants submit their scores</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app.admin>