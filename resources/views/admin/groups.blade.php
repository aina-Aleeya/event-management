<x-layouts.app.admin>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Groups — {{ $event->title }}
                </h2>
                @if(request()->has('category'))
                    <p class="text-sm text-gray-600 mt-1">
                        Category: <span class="font-semibold text-purple-600">{{ request('category') }}</span>
                    </p>
                @endif
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('admin.grouping.index') }}"
                    class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Back to Events
                </a>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                    Dashboard
                </a>
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
                @if(request()->has('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
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
                @if(request()->has('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <label class="font-medium">Max per group (default 5):</label>
                <input type="number" name="max_per_group" class="border rounded-lg px-3 py-2 w-full md:w-1/4" value="5">
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Auto Group
                    Participants</button>
            </form>
        </div>

        {{-- List Groups --}}
        <div class="space-y-6">
            @php
                // Filter groups based on category if provided
                $filteredGroups = request()->has('category') 
                    ? $event->groups->filter(function($group) {
                        return $group->pesertas->where('category', request('category'))->count() > 0;
                    })
                    : $event->groups;
            @endphp

            @forelse ($filteredGroups as $group)
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
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Filter participants based on category if provided
                                    $groupParticipants = request()->has('category')
                                        ? $group->pesertas->where('category', request('category'))
                                        : $group->pesertas;
                                @endphp

                                @forelse ($groupParticipants as $p)
                                    <tr class="border-t hover:bg-gray-50 transition">
                                        <td class="p-3">{{ $p->nama_penuh }}</td>
                                        <td class="p-3">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                {{ $p->category }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex gap-2">
                                                {{-- Move to Another Group --}}
                                                <form method="POST" action="{{ route('admin.group.move', $event->id) }}" class="flex gap-2 items-center">
                                                    @csrf
                                                    <input type="hidden" name="peserta_id" value="{{ $p->id }}">
                                                    <input type="hidden" name="current_group_id" value="{{ $group->id }}">
                                                    @if(request()->has('category'))
                                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                                    @endif
                                                    
                                                    <select name="new_group_id" class="border rounded px-2 py-1 text-sm">
                                                        <option value="">Select group...</option>
                                                        @foreach($filteredGroups as $targetGroup)
                                                            @if($targetGroup->id != $group->id)
                                                                <option value="{{ $targetGroup->id }}">{{ $targetGroup->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    
                                                    <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">
                                                        Move
                                                    </button>
                                                </form>

                                                {{-- Remove from Group --}}
                                                <form method="POST" action="{{ route('admin.group.remove', $event->id) }}" 
                                                      onsubmit="return confirm('Remove {{ $p->nama_penuh }} from this group?')">
                                                    @csrf
                                                    <input type="hidden" name="peserta_id" value="{{ $p->id }}">
                                                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                                                    @if(request()->has('category'))
                                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                                    @endif
                                                    
                                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-3 text-center text-gray-500">
                                            No participants assigned yet
                                            @if(request()->has('category'))
                                                for category "{{ request('category') }}"
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Assign Participant --}}
                    <form method="POST" action="{{ route('admin.group.assign', $event->id) }}"
                        class="mt-4 flex flex-col md:flex-row gap-4 items-center">
                        @csrf
                        <select name="peserta_id" class="border rounded-lg px-3 py-2 w-full md:flex-1">
                            @php
                                // Filter participants based on category if provided
                                $availableParticipants = request()->has('category')
                                    ? $participants->where('category', request('category'))
                                    : $participants;
                            @endphp

                            @forelse($availableParticipants as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penuh }} ({{ $p->category }})</option>
                            @empty
                                <option disabled>No participants available</option>
                            @endforelse
                        </select>

                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        @if(request()->has('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition whitespace-nowrap"
                            @if($availableParticipants->isEmpty()) disabled @endif>
                            Assign to Group
                        </button>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-center">
                        No groups created yet
                        @if(request()->has('category'))
                            for category "{{ request('category') }}"
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app.admin>