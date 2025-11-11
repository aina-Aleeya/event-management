<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-2xl border border-gray-200">
    <h1 class="text-2xl font-semibold mb-4">Registered Participant  List</h1>       

    @forelse($events as $eventId => $group)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-blue-600 mb-3">
                {{ $group[0]->event->title ?? 'Event Tidak Dikenali' }}
            </h2>

            <table class="w-full border border-gray-300 rounded-lg mb-3">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">No.</th>
                        <th class="p-2 border">Full Name</th>
                        <th class="p-2 border">MyKad Number</th>
                        <th class="p-2 border">Club</th>
                        <th class="p-2 border">Category</th>
                        <th class="p-2 border">Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($group as $index => $p)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $index + 1 }}</td>
                            <td class="p-2 border">{{ $p->peserta->nama_penuh ?? '-' }}</td>
                            <td class="p-2 border">{{ $p->peserta->ic ?? '-' }}</td>
                            <td class="p-2 border">{{ $p->peserta->kelas ?? '-' }}</td>
                            <td class="p-2 border">{{ $p->kategori_nama ?? '-' }}</td>
                            <td class="p-2 border">{{ ucfirst($p->status_bayaran ?? '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="text-gray-500">Tiada peserta didaftarkan lagi.</p>
    @endforelse
</div>
