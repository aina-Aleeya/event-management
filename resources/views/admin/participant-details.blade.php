<x-layouts.app.admin>
    {{-- Page Header --}}
    {{-- <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Participant Details — {{ $peserta->nama_penuh }}
            </h2>

            
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
                <a href="" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Reports
                </a>
            </nav>
        </div>
    </x-slot> --}}

    <div class="max-w-4xl mx-auto px-6 py-8">
        {{-- Content Card --}}
        <div class="bg-white shadow rounded-xl p-8">

            {{-- IMAGE --}}
            <div class="flex flex-col items-center mb-8">
                @if ($peserta->gambar)
                    <img src="{{ asset('storage/' . $peserta->gambar) }}" alt="Participant Image"
                        class="w-40 h-40 object-cover rounded-full border shadow">
                @else
                    <div class="w-32 h-32 flex items-center justify-center rounded-full bg-gray-200 text-gray-500">
                        No Image
                    </div>
                @endif
            </div>

            {{-- DETAILS GRID --}}
            @php
                $pivot = $peserta->events->first()?->pivot;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">User ID</p>
                    <p class="font-medium text-gray-800">
                        {{ $pivot->unique_id ?? '-' }}
                    </p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">Category</p>
                    <p class="font-medium text-gray-800">
                        {{ $pivot->kategori ?? '-' }}
                    </p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">Full Name</p>
                    <p class="font-medium text-gray-800">{{ $peserta->nama_penuh }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">Nickname</p>
                    <p class="font-medium text-gray-800">{{ $peserta->nama_panggilan ?? '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">Email</p>
                    <p class="font-medium text-gray-800">{{ $peserta->email ?? '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">Gender</p>
                    <p class="font-medium text-gray-800">{{ $peserta->jantina ?? '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">IC Number</p>
                    <p class="font-medium text-gray-800">{{ $peserta->ic ?? '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">Club</p>
                    <p class="font-medium text-gray-800">{{ $peserta->kelas ?? '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">Date of Birth</p>
                    <p class="font-medium text-gray-800">{{ $peserta->tarikh_lahir ?? '-' }}</p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500 font-semibold">IP Address</p>
                    <p class="font-medium text-gray-800">{{ $peserta->ip_address ?? '-' }}</p>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="mt-8">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition">
                    ← Back
                </a>
            </div>
        </div>
    </div>
</x-layouts.app.admin>
