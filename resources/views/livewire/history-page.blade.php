<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-2xl border border-gray-200">
    <h1 class="text-2xl font-semibold mb-4">History Event Registered</h1>
    <div class="mb-8">
        <table class="w-full border border-gray-300 rounded-lg mb-3">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border text-center">Event</th>
                    <th class="p-2 border text-center">Total Participants </th>
                    <th class="p-2 border text-center">Payment Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historyEvent as $hs)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2  text-center"><a href="{{ route('history.participant', ['eventId' => $hs->event_id]) }}" class="underline">{{$hs->title ?? '-'}}</a></td>
                        <td class="p-2  text-center">{{$hs->total ?? '0'}}</td>
                        <td class="p-2  text-center">
                            @if ($hs->pending_count > 0 && $hs->payment_status === 'Pending')
                                <span class="text-yellow-600 font-semibold">{{ $hs->pending_count }} Pending Payment{{ $hs->pending_count > 1 ? 's' : '' }}</span>
                            @else
                                <span class="text-green-600 font-semibold">All Paid</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
 