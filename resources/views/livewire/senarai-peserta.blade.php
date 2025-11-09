<div class="max-w-xl mx-auto p-6 bg-white shadow-lg rounded-2xl border border-gray-200">
    <h1 class="text-2xl font-semibold mb-4">Senarai Peserta Didaftarkan</h1>
    @if($event)
        <p class="text-lg text-center text-gray-600 mb-5">
            {{ $event->title }}
        </p>
    @endif

    @if($pesertas->isEmpty())
        <p class="text-gray-500">Tiada peserta didaftarkan lagi.</p>
    @else
        <table class="w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">No.</th>
                    <th class="p-2 border">Nama Penuh</th>
                    <th class="p-2 border">IC</th>
                    <th class="p-2 border">Kelab</th>
                    <th class="p-2 border">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesertas as $index => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ $p->peserta->nama_penuh ?? '-'  }}</td>
                        <td class="p-2 border">{{ $p->peserta->ic ?? '-' }}</td>
                        <td class="p-2 border">{{ $p->peserta->kelas ?? '-' }}</td>
                        <td class="p-2 border">{{ $p->kategori_nama ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>