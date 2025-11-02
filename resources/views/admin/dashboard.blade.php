<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-lg mt-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

            <h2 class="text-lg font-semibold mt-4 mb-2">Event Click Statistics</h2>
            <table class="table-auto w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Event</th>
                        <th class="p-2">Click Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr class="border-b">
                            <td class="p-2">{{ $event->title }}</td>
                            <td class="p-2 text-center font-semibold">{{ $event->click_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h2 class="text-lg font-semibold mt-8 mb-2">Participant Summary</h2>
            <table class="table-auto w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Event</th>
                        <th class="p-2">Category</th>
                        <th class="p-2">Total</th>
                        {{-- <th class="p-2">Paid</th>
                <th class="p-2">Unpaid</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($participantSummary as $item)
                        <tr class="border-b">
                            <td class="p-2">{{ $item->title ?? 'N/A' }}</td>
                            <td class="p-2">{{ $item->category }}</td>
                            <td class="p-2">{{ $item->total }}</td>
                            {{-- <td class="p-2 text-green-600">{{ $item->paid_count }}</td>
                <td class="p-2 text-red-600">{{ $item->total - $item->paid_count }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>