<div class="min-h-screen bg-white flex flex-col">
  <div class="flex-grow container mx-auto px-4 py-8 sm:px-6 lg:px-8 max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">My History Events</h1>

    <!-- Event Table Card -->
    <div class="overflow-hidden rounded-xl bg-white border border-gray-500">
      <table class="w-full table-auto text-sm border-separate rounded-xl" style="border-spacing: 0;">
        <thead class="bg-purple-200">
          <tr>
            <th class="px-4 py-3 text-center font-semibold text-gray-800">Event</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-800">Participants</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-800">Payment Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($historyEvent as $event)
            <tr class="cursor-pointer hover:bg-cyan-50 -pointer transition-colors" onclick="window.location='{{ route('history.participant', ['eventId' => $event->event_id]) }}'">
              
              <!-- Event Title -->
              <td class="px-4 py-3 text-center">
                <span class="font-medium text-gray-900">
                  {{ $event->title ?? 'Untitled Event' }}
                </span>
              </td>

              <!-- Participants Count -->
              <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center justify-center text-gray-700 font-semibold">
                  {{ $event->total ?? '0' }}
                </span>
              </td>

              <!-- Payment Status + Arrow -->
              <td class="px-4 py-3 text-center flex items-center justify-center gap-2">
                @if ($event->pending_count > 0 && $event->payment_status === 'Pending')
                  <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $event->pending_count }} Pending{{ $event->pending_count > 1 ? 's' : '' }}
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fas fa-check-circle"></i>
                    All Paid
                  </span>
                @endif
                <!-- Arrow SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 6l6 6-6 6" />
                </svg>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Optional: No Data Message -->
    @if($historyEvent->isEmpty())
      <p class="mt-4 text-gray-500 text-center">No events registered yet.</p>
    @endif
  </div>
</div>
