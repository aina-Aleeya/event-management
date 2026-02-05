<x-layouts.app.admin>
    <x-slot name="breadcrumbs">
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 transition">Events</a>
        </li>
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('organiser.events.team.index', $event->id) }}" class="text-gray-600 hover:text-purple-600 transition">Team</a>
        </li>
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Edit Member</span>
        </li>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto px-6 py-8">

            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Edit Team Member
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $teamMember->getDisplayName() }}</p>
                </div>
                <a href="{{ route('organiser.events.team.index', $event->id) }}" 
                   class="px-5 py-2.5 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Team
                </a>
            </div>

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

            {{-- Form --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <form method="POST" action="{{ route('organiser.events.team.update', [$event->id, $teamMember->id]) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- User Info (Read-only) --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border-2 border-blue-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ $teamMember->getInitials() }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $teamMember->getDisplayName() }}</p>
                                <p class="text-sm text-gray-600">{{ $teamMember->getDisplayEmail() }}</p>
                                
                                {{-- Show Status --}}
                                @if($teamMember->isPending())
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                        Pending Invitation
                                    </span>
                                @elseif($teamMember->isAccepted())
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Select Role --}}
                    <div>
                        <label for="role_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role_id" id="role_id" required
                                class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            <option value="">-- Select Role --</option>
                            
                            {{-- System Roles --}}
                            @if($systemRoles->count() > 0)
                                <optgroup label="Standard Roles">
                                    @foreach ($systemRoles as $role)
                                        <option value="{{ $role->id }}" 
                                                {{ $teamMember->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if($role->description)
                                                - {{ Str::limit($role->description, 40) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            
                            {{-- Custom Roles --}}
                            @if($customRoles->count() > 0)
                                <optgroup label="Your Custom Roles">
                                    @foreach ($customRoles as $role)
                                        <option value="{{ $role->id }}" 
                                                {{ $teamMember->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if($role->description)
                                                - {{ Str::limit($role->description, 40) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        @error('role_id')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Show Current Permissions (Read-only info) --}}
                    @if($teamMember->role)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Current Permissions ({{ $teamMember->getRoleName() }}):
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($teamMember->getPermissions() as $permission)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ config("team_permissions.all_permissions.{$permission}.label", str_replace('_', ' ', ucfirst($permission))) }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500">No permissions assigned</span>
                                @endforelse
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                ⓘ Changing the role will update the permissions automatically
                            </p>
                        </div>
                    @endif

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('organiser.events.team.index', $event->id) }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app.admin>