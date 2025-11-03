<x-layouts.app>
    {{-- Page header --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Participants — {{ $event->title }}
            </h2>

            {{-- Navigation Bar --}}
            <nav class="flex flex-wrap gap-2">
                <a href="" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                    Dashboard
                </a>
                <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Events
                </a>
                <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Participants
                </a>
                {{-- <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Reports
                </a> --}}
            </nav>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-xl font-semibold mb-4">Participant List</h1>

            <div class="overflow-x-auto">
                <table class="w-full shadow-sm rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm sticky top-0">
                        <tr>
                            <th class="p-3 text-left">Name</th>
                            <th class="p-3 text-left">Category</th>
                            <th class="p-3 text-left">Unique ID</th>
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
                            </tr>
                        @endforeach

                        @if ($participants->isEmpty())
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">
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