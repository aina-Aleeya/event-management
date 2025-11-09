<x-layouts.app>
    {{-- Page header --}}
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"></div>
    <div class="flex justify-end mb-3">
        <a href="{{ route('admin.events.report', $event->id) }}"
            class="px-2 py-1 bg-purple-300 text-gray-900 rounded-lg hover:bg-sky-300 transition">
            Generate Report
        </a>
    </div>


    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-xl font-semibold mb-4">Participant List — {{ $event->title }}</h1>

            <div class="overflow-x-auto">
                <table class="w-full shadow-sm rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm sticky top-0">
                        <tr>
                            <th class="p-3 text-left">Name</th>
                            <th class="p-3 text-left">Category</th>
                            <th class="p-3 text-left">Unique ID</th>
                            <th class="p-3 text-left">Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participants as $p)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="p-3">
                                    <a href="{{ route('admin.participant.view', $p->id) }}"
                                        class="text-blue-600 hover:text-blue-800 underline">
                                        {{ $p->nama_penuh }}
                                    </a>
                                </td>
                                <td class="p-3">{{ $p->pivot->kategori_nama }}</td>
                                <td class="p-3 font-medium text-gray-700">{{ $p->pivot->unique_id }}</td>
                                <td class="p-3">
                                    @if ($p->pivot->status_bayaran === 'complete')
                                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                                            Completed
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 font-medium">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($participants->isEmpty())
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    No participants have registered yet.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Back Button --}}
            <div class="mt-6">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition">
                    ← Back
                </a>
            </div>

        </div>

    </div>
</x-layouts.app>