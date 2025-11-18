<x-layouts.app.admin>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Groups — {{ $event->title }}
            </h2>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                    Dashboard
                </a>
                {{-- <a href="{{ route('admin.events') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Events
                </a> --}}
            </nav>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

        {{-- Create Group --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Create New Group</h3>
            <form method="POST" action="{{ route('admin.group.store', $event->id) }}"
                class="flex flex-col md:flex-row gap-4 items-center">
                @csrf
                <label class="font-medium">Group Name:</label>
                <input type="text" name="name" class="border rounded-lg px-3 py-2 w-full md:w-1/3" required>
                <label class="font-medium">Capacity (optional):</label>
                <input type="number" name="capacity" class="border rounded-lg px-3 py-2 w-full md:w-1/4">
                <button type="submit"
                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">Create
                    Group</button>
            </form>
        </div>

        {{-- Auto Grouping --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Auto-Grouping</h3>
            <form method="POST" action="{{ route('admin.group.auto', $event->id) }}"
                class="flex flex-col md:flex-row gap-4 items-center">
                @csrf
                <label class="font-medium">Max per group (default 5):</label>
                <input type="number" name="max_per_group" class="border rounded-lg px-3 py-2 w-full md:w-1/4">
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Auto Group
                    Participants</button>
            </form>
        </div>

        {{-- List Groups --}}
        <div class="space-y-6">
            @forelse ($event->groups as $group)
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">{{ $group->name }}
                            <span class="text-sm text-gray-500">({{ $group->capacity ?? 'No limit' }})</span>
                        </h3>
                    </div>

                    {{-- Participant Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                                <tr>
                                    <th class="p-3">Name</th>
                                    <th class="p-3">Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($group->pesertas as $p)
                                    <tr class="border-t hover:bg-gray-50 transition">
                                        <td class="p-3">{{ $p->nama_penuh }}</td>
                                        <td class="p-3">{{ $p->category }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="p-3 text-center text-gray-500">No participants
                                            assigned yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Assign Participant --}}
                    <form method="POST" action="{{ route('admin.group.assign', $event->id) }}"
                        class="mt-4 flex flex-col md:flex-row gap-4 items-center">
                        @csrf
                        {{-- <select name="peserta_id" class="border rounded-lg px-3 py-2 w-full md:w-1/3">
                            @foreach ($participants as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penuh }}</option>
                            @endforeach
                        </select> --}}

                        <select name="peserta_id">
                            @foreach ($participants as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penuh }} ({{ $p->category }})
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Assign to
                            Group</button>
                    </form>
                </div>
            @empty
                <p class="text-gray-500">No groups created yet.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app.admin>
