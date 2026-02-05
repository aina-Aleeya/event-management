<x-layouts.app.admin>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Team Members
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $event->title }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('organiser.events.team.create', $event->id) }}"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 transition-all font-medium flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Invite Member
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-6 py-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Team Members List --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-slate-50 to-blue-50">
                    <h3 class="text-lg font-bold text-gray-800">Team Members ({{ $event->teamMembers->count() }})</h3>
                </div>

                @forelse($event->teamMembers as $member)
                    <div class="p-6 border-b border-gray-100 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between gap-4">
                            {{-- Member Info --}}
                            <div class="flex items-center gap-4">
                                {{-- Avatar --}}
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-600 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    {{ $member->getInitials() }}
                                </div>

                                {{-- Details --}}
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-gray-800">{{ $member->getDisplayName() }}</h4>
                                        
                                        {{-- Status Badge --}}
                                        @if($member->status === 'pending')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                Pending
                                            </span>
                                        @elseif($member->status === 'accepted')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                Accepted
                                            </span>
                                        @elseif($member->status === 'declined')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                Declined
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-sm text-gray-600">{{ $member->getDisplayEmail() }}</p>
                                    
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                            {{ $member->role->name ?? 'No Role' }}
                                        </span>
                                        
                                        @if($member->invited_at)
                                            <span class="text-xs text-gray-500">
                                                Invited {{ $member->invited_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                {{-- Resend Invitation (Only for pending) --}}
                                @if($member->status === 'pending')
                                    <form method="POST" action="{{ route('organiser.events.team.resend', [$event->id, $member->id]) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                                            Resend Invitation
                                        </button>
                                    </form>
                                @endif

                                {{-- Change Role --}}
                                <button onclick="openChangeRoleModal({{ $member->id }}, '{{ $member->role->name ?? '' }}')"
                                        class="px-3 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition text-sm font-medium">
                                    Change Role
                                </button>

                                {{-- Remove --}}
                                <form method="POST" 
                                      action="{{ route('organiser.events.team.destroy', [$event->id, $member->id]) }}"
                                      onsubmit="return confirm('Remove {{ $member->name }} from this event team?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Show Permissions --}}
                        @if($member->role && count($member->role->permissions) > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs font-semibold text-gray-600 mb-2">Permissions:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($member->role->permissions as $permission)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                            {{ config("team_permissions.all_permissions.{$permission}.label", $permission) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">No Team Members Yet</h3>
                        <p class="text-gray-500 mb-6">Start building your team by inviting members</p>
                        <a href="{{ route('organiser.events.team.create', $event->id) }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Invite First Member
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app.admin>