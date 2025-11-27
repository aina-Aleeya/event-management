<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8 font-sans text-slate-900">

    {{-- Header --}}
    <header class="max-w-3xl mx-auto mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-black tracking-tight">
            Ranking Report: {{ $event->title }}
        </h1>
        <p class="mt-2 text-lg text-gray-600">
            Manage Winners
        </p>
    </header>

    <main class="max-w-6xl mx-auto">

        {{-- Category Tabs --}}
        <div class="bg-white shadow-sm rounded-lg border border-white overflow-hidden mb-8">

            <div class="px-3 pt-3 pb-2">
                <h2 class="text-xl font-semibold text-gray-900">Competition Rankings</h2>
            </div>


            {{-- Category Buttons --}}
            <div class="px-6 flex space-x-8 border-b border-gray-200 mb-6">
                <button wire:click="changeCategory('Individu')"
                    class="pb-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors
                        {{ $category === 'Individu' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-user"></i> Individual
                </button>
                <button wire:click="changeCategory('Berkumpulan')"
                    class="pb-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors
                        {{ $category === 'Berkumpulan' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-users"></i> Group
                </button>
            </div>

            {{-- Form + Leaderboard --}}
            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- Left Column: Winner Input + Search + Suggestions --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-gray-50 p-5 rounded-md border border-gray-200">
                        <h3
                            class="text-sm font-medium text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">
                            Enter Results
                        </h3>

                        <div class="space-y-5">
                            @foreach([1, 2, 3] as $slot)
                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $slot == 1 ? 'Rank 1' : ($slot == 2 ? 'Rank 2' : 'Rank 3') }}
                                    </label>

                                    {{-- Search Input --}}
                                    <input type="text" wire:model.live="search.{{ $slot }}"
                                        placeholder="Search name or group..."
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 pr-10 border">
                                    <i class="fas fa-search text-gray-400 absolute right-3 top-8"></i>

                                    {{-- Suggestions --}}
                                    @if(!empty($suggestions[$slot]))
                                        <div
                                            class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow max-h-40 overflow-y-auto">
                                            @foreach($suggestions[$slot] as $s)
                                                <div wire:click="selectSuggestion({{ $s['id'] }}, {{ $slot }})"
                                                    class="px-4 py-2 hover:bg-blue-50 cursor-pointer">
                                                    <strong>{{ $s['name'] }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Selected Name --}}
                                    @if(isset($selectedNames[$slot]) && $selectedNames[$slot])
                                        <div
                                            class="bg-white/60 backdrop-blur-sm p-3 rounded-lg border border-slate-200 text-sm mt-2">
                                            <div class="text-xs font-semibold text-slate-400 uppercase mb-1">Selected</div>
                                            <div class="font-medium text-slate-800">{{ $selectedNames[$slot] }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button wire:click="save"
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-floppy-disk mr-2"></i>
                            Save Ranking
                        </button>
                        <button wire:click="resetRanking"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-rotate-left mr-2"></i>
                            Reset
                        </button>
                    </div>
                </div>

                {{-- Right Column: Published Leaderboard --}}
                <div class="lg:col-span-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3
                            class="text-sm font-medium text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-200">
                            Published Leaderboard
                        </h3>

                        <a href="{{ route('organiser.event.ranking.export', $event->id) }}"
                            class="inline-flex items-center px-2 py-1 bg-green-600 border border-transparent rounded-md shadow-sm text-xs font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="fas fa-file-excel mr-1"></i>
                            Export
                        </a>
                    </div>


                    @if($miniLeaderboard->isEmpty())
                        <div
                            class="flex flex-col items-center justify-center h-64 text-gray-400 bg-gray-50 rounded-lg border-2 border-dashed">
                            <i class="fas fa-info-circle text-2xl mb-2"></i>
                            <span class="text-sm font-medium">No rankings published yet</span>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($miniLeaderboard as $item)
                                @php
                                    $pen = $item->penyertaan;
                                    $rankColors = [
                                        1 => 'bg-yellow-50 border-yellow-200 text-yellow-600',
                                        2 => 'bg-gray-50 border-gray-200 text-gray-600',
                                        3 => 'bg-orange-50 border-orange-200 text-orange-600',
                                    ];
                                    $color = $rankColors[$item->ranking] ?? 'bg-white border-gray-200 text-gray-800';
                                @endphp
                                <div
                                    class="p-4 rounded-lg border {{ $color }} flex items-start gap-4 shadow-sm hover:shadow-md transition">
                                    <i class="fas fa-trophy mt-1"></i>
                                    <div>
                                        <div class="text-xs font-bold uppercase tracking-wide opacity-75 mb-0.5">
                                            {{ $item->ranking == 1 ? '1st Place' : ($item->ranking == 2 ? '2nd Place' : '3rd Place') }}
                                        </div>
                                        <div class="font-bold text-lg leading-tight">
                                            @if($category === 'Berkumpulan' && isset($item->group))
                                                {{ $item->group->name }}
                                                <ul class="text-sm mt-1 text-gray-600 list-disc list-inside">
                                                    @foreach($item->group->pesertas as $m)
                                                        <li>{{ $m->nama_penuh }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{ $pen->peserta->nama_penuh }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif


                </div>
            </div>

        </div>
</div>
</main>

</div>