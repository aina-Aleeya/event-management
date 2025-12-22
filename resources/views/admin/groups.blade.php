<x-layouts.app.admin>
    <div class="max-w-7xl mx-auto px-6 py-6">

        <!-- Page Header -->
        <div class="mb-6 mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }} - Group Management</h1>
                    @if(request()->has('category'))
                        <div class="flex items-center gap-2 mt-2">
                            <p class="text-sm text-gray-600">Category:</p>
                            <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold border border-purple-200">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                                {{ request('category') }}
                            </span>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mt-1">Organize participants into groups</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Auto-Grouping Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Auto-Grouping</h3>
                        <p class="text-sm text-gray-600">Automatically distribute participants into groups</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.group.auto', $event->id) }}"
                    class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    @if(request()->has('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum per group:</label>
                        <input type="number" name="max_per_group" 
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                               value="5" min="1">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-blue-600 border border-blue-600 rounded-lg text-sm font-semibold text-white hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Auto Group Participants
                    </button>
                </form>
            </div>
        </div>

        <!-- Groups List Section -->
        <div class="space-y-6">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Group List</h3>
                        <p class="text-sm text-gray-600">Manage and organize group members</p>
                    </div>
                </div>
                <a href="{{ route('admin.scoresheet.export-all-groups', ['event' => $event->id, 'category' => request('category')]) }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-300 rounded-lg text-sm font-semibold text-indigo-700 hover:from-indigo-100 hover:to-indigo-200 hover:shadow-md transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export All Scoresheets
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

            <!-- Groups -->
            @forelse ($filteredGroups as $group)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all border border-gray-200 overflow-hidden">
                    
                    <!-- Group Header -->
                    <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span>
                                    {{ $group->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-sm text-gray-600 font-medium">Capacity:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold">
                                        {{ $group->capacity ?? 'No limit' }}
                                    </span>
                                    @php
                                        $groupParticipants = request()->has('category')
                                            ? $group->pesertas->where('category', request('category'))
                                            : $group->pesertas;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ $groupParticipants->count() }} members
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('admin.scoresheet.export-group', ['event' => $event->id, 'group' => $group->id]) }}"
                               class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-300 rounded-lg text-sm font-semibold text-indigo-700 hover:from-indigo-100 hover:to-indigo-200 hover:shadow-md transition-all whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export Scoresheet
                            </a>
                        </div>
                    </div>

                    <!-- Participant Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-200 bg-gray-50">
                                    <th class="px-6 py-4">Participant Name</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @php
                                    // Filter participants based on category if provided
                                    $groupParticipants = request()->has('category')
                                        ? $group->pesertas->where('category', request('category'))
                                        : $group->pesertas;
                                @endphp

                                @forelse ($groupParticipants as $p)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <!-- Name -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $p->nama_penuh }}</div>
                                        </td>
                                        
                                        <!-- Category -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $p->category }}
                                            </span>
                                        </td>
                                        
                                        <!-- Actions -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                {{-- Move to Another Group --}}
                                                <form method="POST" action="{{ route('admin.group.move', $event->id) }}" class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="peserta_id" value="{{ $p->id }}">
                                                    <input type="hidden" name="current_group_id" value="{{ $group->id }}">
                                                    @if(request()->has('category'))
                                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                                    @endif
                                                    
                                                    <select name="new_group_id" class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                                        <option value="">Move to group...</option>
                                                        @foreach($filteredGroups as $targetGroup)
                                                            @if($targetGroup->id != $group->id)
                                                                <option value="{{ $targetGroup->id }}">{{ $targetGroup->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
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
                                                    
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-all shadow-sm hover:shadow-md">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <p class="text-gray-500 font-medium">No participants assigned yet</p>
                                                @if(request()->has('category'))
                                                    <p class="text-gray-400 text-sm mt-1">for category "{{ request('category') }}"</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-16 text-center border border-gray-200">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-20 h-20 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">No Groups Created Yet</h3>
                        @if(request()->has('category'))
                            <p class="text-gray-500 mb-4">for category "{{ request('category') }}"</p>
                        @else
                            <p class="text-gray-500 mb-4">Create your first group using the auto-grouping feature above</p>
                        @endif
                        <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium border border-blue-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Use Auto-Grouping to get started
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</x-layouts.app.admin>