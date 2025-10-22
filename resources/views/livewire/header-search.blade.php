<div class="relative w-full max-w-xs">
    <!-- Search Input with Icon -->
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-purple-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </span>

        <input
            type="text"
            wire:model.debounce.300ms="query"
            placeholder="Search events..."
            class="w-full pl-10 pr-3 py-2 rounded-lg border border-purple-300 bg-purple-100
                   text-gray-900 focus:ring-2 focus:ring-purple-300 focus:outline-none"
        />
    </div>

    <!-- Results Dropdown -->
    @if(strlen($query) > 1)
        <div class="absolute left-0 mt-2 w-full bg-white border border-purple-200 rounded-lg shadow-lg z-50">
            @forelse($results as $event)
                <a href="{{ $event->link ?? '#' }}"
                   class="block px-4 py-2 hover:bg-purple-50 transition">
                    <div class="font-medium text-purple-900">{{ $event->title }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') : '' }}
                        @if($event->location)
                            - {{ $event->location }}
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-4 py-2 text-sm text-gray-500">No results found</div>
            @endforelse
        </div>
    @endif
</div>