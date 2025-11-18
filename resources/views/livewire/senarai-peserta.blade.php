<div class="max-w-4xl mx-auto p-6 ">
    <h1 class="text-2xl font-semibold mb-4">Registered Participant  List</h1>       

    @if($registrations->isEmpty())
        <p class="text-gray-500">Tiada peserta didaftarkan lagi.</p>
    @else
        <h2 class="text-xl font-semibold text-center text-orange-400 mb-3">
            {{ $registrations->first()->event->title ?? 'Event Tidak Dikenali' }}
        </h2>

        <table class="table-fixed w-full border border-gray-300 rounded-lg mb-3">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border text-center w-12">No.</th>
                    <th class="p-2 border text-center">Full Name</th>
                    <th class="p-2 border text-center">MyKad Number</th>
                    <th class="p-2 border text-center">Club</th>
                    <th class="p-2 border text-center">Category</th>
                    <th class="p-2 border text-center">Payment Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $index => $reg)
                    <tr>
                        <td class="p-2 border text-center w-12">{{ $index + 1 }}</td>
                        <td class="p-2 border text-center">{{ $reg->peserta->nama_penuh ?? '-' }}</td>
                        <td class="p-2 border text-center">{{ $reg->peserta->ic ?? '-' }}</td>
                        <td class="p-2 border text-center">{{ $reg->peserta->kelas ?? '-' }}</td>
                        <td class="p-2 border text-center">{{ $reg->kategori_nama ?? '-' }}</td>
                        <td class="p-2 border text-center">{{ ucfirst($reg->status_bayaran ?? '-') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-between mt-4 px-8">
            <a href="{{route('history')}}"
               class="hover:underline hover:text-yellow-700 transition">
               <- Back
            </a>
            @if($registrations->contains('status_bayaran', 'pending'))
                <a href="{{ route('payment.form', ['event_id' => $eventId]) }}">Pay</a>
            @endif
        </div>
    @endif
</div>
