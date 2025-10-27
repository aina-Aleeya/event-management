<div class="w-transparent bg-white dark:bg-zinc-800 border-b border-purple-200 dark:border-zinc-700 shadow-sm py-3">
    <div class="relative w-70 ml-auto">

        <!-- Search Input -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>


            <input wire:model.live="query" type="search" placeholder="Search events..." class="block w-full p-2.5 pl-9 text-sm text-gray-900 border border-gray-300 rounded-lg 
                          bg-gray-50 focus:ring-purple-500 focus:border-purple-500 
                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white 
                          dark:focus:ring-purple-500 dark:focus:border-purple-500" />
        </div>

        <!--Auto-suggest dropdown -->
        @if (!empty($results))
            <ul class="absolute w-full mt-2 bg-white dark:bg-zinc-700 border border-purple-100 dark:border-zinc-700 
                           rounded-lg divide-y divide-purple-100 dark:divide-zinc-600 shadow-lg z-50">
                @foreach ($results as $event)
                    <li class="p-3 hover:bg-purple-50 dark:hover:bg-zinc-600 transition">
                        <a href="{{ route('event.details', $event->id) }}"
                            class="block text-gray-800 dark:text-gray-100 font-medium">
                            {{ $event->title }}
                        </a>
                        <p class="text-xs text-gray-500">{{ $event->created_at->format('d M Y') }}</p>
                    </li>
                @endforeach
            </ul>
        @elseif(strlen($query) > 1)
            <div
                class="absolute w-full mt-2 p-3 text-sm text-gray-500 bg-white dark:bg-zinc-700 rounded-lg text-center shadow">
                No results found.
            </div>
        @endif
    </div>
</div>