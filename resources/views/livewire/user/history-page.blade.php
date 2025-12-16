<div class="min-h-screen bg-gray-50 flex flex-col">
  <div class="flex-grow container mx-auto px-4 py-8 sm:px-6 lg:px-8 max-w-7xl">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 tracking-tight">My History Events</h1>
    </div>

    <!-- Event Table -->
    <div class="overflow-hidden rounded-xl shadow-lg bg-white">
      <table class="w-full table-auto border-collapse text-sm">
        <thead>
          <tr class="bg-gray-200 text-left">
            <th class="px-6 py-4 font-semibold text-gray-600">Event Details</th>
            <th class="px-6 py-4 font-semibold text-gray-600">Participants</th>
            <th class="px-6 py-4 font-semibold text-gray-600">Payment Status</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach ($historyEvent as $event)
            <tr class="group hover:bg-blue-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('history.participant', ['eventId' => $event->event_id]) }}'">
              <td class="px-6 py-4">
                <div class="flex flex-col">
                  <span class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                    {{ $event->title ?? 'Untitled Event' }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex justify-center items-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm">
                  {{ $event->total ?? '0' }}
                </div>
              </td>
              <td class="px-6 py-4">
                @if ($event->pending_count > 0 && $event->payment_status === 'Pending')
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                    <i class="fas fa-exclamation-circle w-3.5 h-3.5"></i>
                    {{ $event->pending_count }} Pending Payment{{ $event->pending_count > 1 ? 's' : '' }}
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fas fa-check-circle w-3.5 h-3.5"></i>
                    All Paid
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 text-right">
                <span class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transition-colors inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6l6 6-6 6" />
                  </svg>
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
