<x-layouts.app>

    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-lg mt-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Participants for {{ $event->title }}</h1>

            <table class="table-auto w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">User ID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Category</th>
                        {{-- <th class="p-2">Payment</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($participants as $p)
                        <tr class="border-b">
                            <td class="p-2">{{ $p->id }}</td>
                            <td class="p-2">{{ $p->nama_penuh}}</td>
                            <td class="p-2">{{ $p->category }}</td>
                            {{-- <td class="p-2">{{ $p->payment_status ? 'Paid' : 'Unpaid' }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
