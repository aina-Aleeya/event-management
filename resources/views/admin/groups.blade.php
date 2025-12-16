<x-layouts.app.admin>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Groups — {{ $event->title }}
                </h2>
                @if(request()->has('category'))
                    <p class="text-sm text-gray-600 mt-2 flex items-center gap-2">
                        <span class="text-gray-500">Category:</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                            {{ request('category') }}
                        </span>
                    </p>
                @endif
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="{{ route('admin.grouping.index') }}"
                    class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-gray-100 to-slate-100 text-gray-700 hover:from-gray-200 hover:to-slate-200 transition-all font-medium flex items-center gap-2 shadow-sm hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Events
                </a>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700 transition-all font-medium flex items-center gap-2 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
            {{-- Auto Grouping --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Auto-Grouping</h3>
                        <p class="text-xs text-gray-600">Automatically distribute participants into groups</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.group.auto', $event->id) }}"
                    class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    @if(request()->has('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Max per group:</label>
                        <input type="number" name="max_per_group" 
                               class="border-2 border-gray-200 rounded-lg px-4 py-2.5 w-full focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                               value="5" min="1">
                    </div>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Auto Group Participants
                    </button>
                </form>
            </div>

            {{-- List Groups --}}
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Group List</h3>
                    </div>
                    <a href="{{ route('admin.scoresheet.export-all-groups', ['event' => $event->id, 'category' => request('category')]) }}"
                       class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all text-sm flex items-center gap-2 font-semibold shadow-lg hover:shadow-xl whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export All Groups Scoresheet
                    </a>
                </div>

                @php
                    // Filter groups based on category if provided
                    $filteredGroups = request()->has('category') 
                        ? $event->groups->filter(function($group) {
                            return $group->pesertas->where('category', request('category'))->count() > 0;
                        })
                        : $event->groups;
                @endphp

                @forelse ($filteredGroups as $group)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all border border-gray-100 overflow-hidden">
                        {{-- Group Header --}}
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-6 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        {{ $group->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-semibold">Capacity:</span> 
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                            {{ $group->capacity ?? 'No limit' }}
                                        </span>
                                    </p>
                                </div>
                                <a href="{{ route('admin.scoresheet.export-group', ['event' => $event->id, 'group' => $group->id]) }}"
                                   class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all text-sm flex items-center gap-2 font-semibold shadow-lg hover:shadow-xl whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Scoresheet
                                </a>
                            </div>
                        </div>

                        {{-- Participant Table --}}
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 uppercase text-xs border-b-2 border-gray-200">
                                        <tr>
                                            <th class="p-4 font-semibold">Name</th>
                                            <th class="p-4 font-semibold">Category</th>
                                            <th class="p-4 font-semibold">Actions</th>
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
                                            <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                                <td class="p-4 font-medium text-gray-800">{{ $p->nama_penuh }}</td>
                                                <td class="p-4">
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                                        {{ $p->category }}
                                                    </span>
                                                </td>
                                                <td class="p-4">
                                                    <div class="flex gap-2">
                                                        {{-- Move to Another Group --}}
                                                        <form method="POST" action="{{ route('admin.group.move', $event->id) }}" class="flex gap-2 items-center">
                                                            @csrf
                                                            <input type="hidden" name="peserta_id" value="{{ $p->id }}">
                                                            <input type="hidden" name="current_group_id" value="{{ $group->id }}">
                                                            @if(request()->has('category'))
                                                                <input type="hidden" name="category" value="{{ request('category') }}">
                                                            @endif
                                                            
                                                            <select name="new_group_id" class="border-2 border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                                                <option value="">Select group...</option>
                                                                @foreach($filteredGroups as $targetGroup)
                                                                    @if($targetGroup->id != $group->id)
                                                                        <option value="{{ $targetGroup->id }}">{{ $targetGroup->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            
                                                            <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-all font-medium shadow-md hover:shadow-lg">
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
                                                            
                                                            <button type="submit" class="px-4 py-1.5 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700 transition-all font-medium shadow-md hover:shadow-lg">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="p-8 text-center">
                                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                                    </svg>
                                                    <p class="text-gray-500 text-sm">
                                                        No participants assigned yet
                                                        @if(request()->has('category'))
                                                            for category "{{ request('category') }}"
                                                        @endif
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">
                            No groups created yet
                            @if(request()->has('category'))
                                for category "{{ request('category') }}"
                            @endif
                        </p>
                        <p class="text-gray-400 text-sm mt-2">Use the Auto-Grouping feature above to get started</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app.admin>