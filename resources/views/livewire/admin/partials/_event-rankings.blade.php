<div class="mb-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-amber-50 rounded flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <h2 class="text-m font-semibold text-gray-900">Live Ranking Leaderboard</h2>
        </div>
        <a href="{{ route('admin.ranking.show', $event->id) }}"
            class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg hover:bg-gray-800 transition-colors">
            View All
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @php
        // Get all categories
        $eventCategories = \Illuminate\Support\Facades\DB::table('penyertaan')
            ->leftJoin('categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
            })
            ->leftJoin('custom_categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
            })
            ->where('penyertaan.event_id', $event->id)
            ->select(\Illuminate\Support\Facades\DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();
    @endphp

    @if($eventCategories->count() > 0)
        <!-- Grid: 2-3 categories per row -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($eventCategories as $category)
                @php
                    // Get top 3 for this category
                    $topScores = \App\Models\Score::where('event_id', $event->id)
                        ->with(['peserta', 'group'])
                        ->whereNotNull('average')
                        ->get()
                        ->filter(function($score) use ($category, $event) {
                            $categoryData = \Illuminate\Support\Facades\DB::table('penyertaan')
                                ->leftJoin('categories', function($join) {
                                    $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                                         ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
                                })
                                ->leftJoin('custom_categories', function($join) {
                                    $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                                         ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
                                })
                                ->where('penyertaan.event_id', $event->id)
                                ->where('penyertaan.peserta_id', $score->peserta_id)
                                ->select(\Illuminate\Support\Facades\DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                                ->first();
                            
                            return $categoryData && $categoryData->category === $category;
                        })
                        ->sortByDesc('average')
                        ->take(3)
                        ->values();

                    $first = $topScores->get(0);
                    $second = $topScores->get(1);
                    $third = $topScores->get(2);
                @endphp

                <!-- Category Box -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Category Header -->
                    <div class="bg-gray-900 px-4 py-3 text-center">
                        <h3 class="text-sm font-semibold text-white">{{ $category }}</h3>
                    </div>

                    <!-- Podium -->
                    <div class="p-5">
                        @if($topScores->count() > 0)
                            <div class="flex items-end justify-center gap-2">
                                
                                <!-- 2nd Place -->
                                @if($second)
                                <div class="flex flex-col items-center flex-1">
                                    <div class="relative mb-2">
                                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-slate-300 to-slate-400 flex items-center justify-center text-white font-semibold text-sm shadow-md border-2 border-white">
                                            {{ strtoupper(substr($second->peserta->nama_penuh, 0, 2)) }}
                                        </div>
                                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-5 h-5 bg-slate-500 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">
                                            2
                                        </div>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-t-md" style="height: 55px;">
                                        <div class="h-full flex flex-col items-center justify-center px-2">
                                            <p class="text-xs font-medium text-center line-clamp-1 text-gray-900">{{ $second->peserta->nama_penuh }}</p>
                                            <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ number_format($second->average, 1) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- 1st Place -->
                                @if($first)
                                <div class="flex flex-col items-center flex-1">
                                    <svg class="w-5 h-5 text-amber-500 mb-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <div class="relative mb-2">
                                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 flex items-center justify-center text-white font-bold shadow-lg border-2 border-white">
                                            {{ strtoupper(substr($first->peserta->nama_penuh, 0, 2)) }}
                                        </div>
                                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold border-2 border-white">
                                            1
                                        </div>
                                    </div>
                                    <div class="w-full bg-amber-100 rounded-t-md" style="height: 75px;">
                                        <div class="h-full flex flex-col items-center justify-center px-2">
                                            <p class="text-xs font-medium text-center line-clamp-1 text-gray-900">{{ $first->peserta->nama_penuh }}</p>
                                            <p class="text-base font-bold text-amber-700 mt-1">{{ number_format($first->average, 1) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- 3rd Place -->
                                @if($third)
                                <div class="flex flex-col items-center flex-1">
                                    <div class="relative mb-2">
                                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-orange-300 to-orange-400 flex items-center justify-center text-white font-semibold text-sm shadow-md border-2 border-white">
                                            {{ strtoupper(substr($third->peserta->nama_penuh, 0, 2)) }}
                                        </div>
                                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white">
                                            3
                                        </div>
                                    </div>
                                    <div class="w-full bg-orange-100 rounded-t-md" style="height: 45px;">
                                        <div class="h-full flex flex-col items-center justify-center px-2">
                                            <p class="text-xs font-medium text-center line-clamp-1 text-gray-900">{{ $third->peserta->nama_penuh }}</p>
                                            <p class="text-sm font-semibold text-orange-700 mt-0.5">{{ number_format($third->average, 1) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-400 text-center text-sm py-8">No scores yet</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-500 text-base font-medium">No categories available</p>
        </div>
    @endif
</div>