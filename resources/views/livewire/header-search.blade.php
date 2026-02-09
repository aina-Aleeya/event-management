<div class="w-full">
    <div class="relative max-w-xl mx-auto">

        <!-- Search Input with Icon -->
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <svg class="w-5 h-5 text-purple-400 group-focus-within:text-purple-600 transition-colors" 
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>

            <input wire:model.live="query" 
                   type="search" 
                   placeholder="Search for events, concerts, conferences..." 
                   class="block w-full pl-12 pr-4 py-3.5 text-gray-900 bg-white border-2 border-gray-200 
                          rounded-xl shadow-sm placeholder:text-gray-400
                          focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none
                          transition-all duration-200
                          dark:bg-zinc-800 dark:border-zinc-600 dark:text-white dark:placeholder:text-gray-500
                          dark:focus:border-purple-500 dark:focus:ring-purple-900/30" />
        </div>

        <!-- Auto-suggest Dropdown -->
        @if (!empty($results))
            <div class="absolute w-full mt-3 bg-white dark:bg-zinc-800 rounded-xl shadow-2xl border border-gray-100 dark:border-zinc-700 overflow-hidden z-50 animate-in fade-in slide-in-from-top-2 duration-200">
                <div class="max-h-96 overflow-y-auto">
                    @foreach ($results as $event)
                        <a href="{{ route('event.details', $event->id) }}"
                           class="block p-4 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 
                                  dark:hover:from-purple-900/20 dark:hover:to-pink-900/20
                                  border-b border-gray-100 dark:border-zinc-700 last:border-b-0
                                  transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                        {{ $event->title }}
                                    </h3>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $event->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @elseif(strlen($query) > 1)
            <div class="absolute w-full mt-3 p-8 bg-white dark:bg-zinc-800 rounded-xl shadow-2xl border border-gray-100 dark:border-zinc-700 z-50 animate-in fade-in slide-in-from-top-2 duration-200">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">No events found</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Try searching with different keywords</p>
                </div>
            </div>
        @endif
    </div>
</div>