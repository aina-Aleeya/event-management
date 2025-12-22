<div class="min-h-screen bg-white flex flex-col">
  <div class="flex-grow container mx-auto px-4 py-8 sm:px-6 lg:px-8 max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Registered Participant List</h1>

    @if($registrations->isEmpty())
      <p class="text-gray-500 text-center">Tiada peserta didaftarkan lagi.</p>
    @else
      <h2 class="text-xl font-semibold text-center text-slate-800 mb-6">
        {{ $registrations->first()->event->title ?? 'Event Tidak Dikenali' }}
      </h2>

      <!-- Participants Table Card -->
      <div class="overflow-hidden rounded-xl bg-white border border-gray-400">
        <table class="w-full table-auto text-sm border-separate rounded-xl" style="border-spacing: 0;">
          <thead class="bg-stone-300 ">
            <tr>
              <th class="px-4 py-3 text-center font-semibold text-gray-800 w-12">No.</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-800">Full Name</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-800">MyKad Number</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-800">Club</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-800">Category</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-800">Payment Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($registrations as $index => $reg)
              <tr class="cursor-pointer transition-colors">
                <td class="px-4 py-3 text-center font-medium">{{ $index + 1 }}</td>
                <td class="px-4 py-3 text-center">{{ $reg->peserta->nama_penuh ?? '-' }}</td>
                <td class="px-4 py-3 text-center">{{ $reg->peserta->ic ?? '-' }}</td>
                <td class="px-4 py-3 text-center">{{ $reg->peserta->kelas ?? '-' }}</td>
                <td class="px-4 py-3 text-center">{{ $reg->category_name ?? '-' }}</td>
                <td class="px-4 py-3 text-center flex items-center justify-center gap-2">
                  @if($reg->status_bayaran === 'pending')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                      {{ ucfirst($reg->status_bayaran) }}
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                      {{ ucfirst($reg->status_bayaran) }}
                    </span>
                  @endif
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Action Links -->
      <div class="flex justify-between mt-6">
        <a href="{{route('history')}}" class="px-4 py-2 rounded-lg bg-slate-200 text-gray-800 hover:bg-slate-300 transition font-medium">
          &larr; Back
        </a>
        @if($registrations->contains('status_bayaran', 'pending'))
          <a href="{{ route('payment.form', ['event_id' => $eventId]) }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition font-medium">
            Pay
          </a>
        @endif
      </div>
    @endif
  </div>
</div>
