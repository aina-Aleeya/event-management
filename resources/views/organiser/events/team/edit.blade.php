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
            <a href="{{ route('admin.events.team.index', $event->id) }}" class="text-gray-600 hover:text-purple-600 transition">Team</a>
        </li>
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Edit Member</span>
        </li>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="max-w-4xl mx-auto px-6 py-8">

            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Edit Team Member
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $teamMember->user->name }}</p>
                </div>
                <a href="{{ route('admin.events.team.index', $event->id) }}" 
                   class="px-5 py-2.5 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Team
                </a>
            </div>

            {{-- Form --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <form method="POST" action="{{ route('admin.events.team.update', [$event->id, $teamMember->id]) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- User Info (Read-only) --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border-2 border-blue-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ $teamMember->user->initials() }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $teamMember->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $teamMember->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Select Role --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                        <select name="role" id="role" required
                                class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                onchange="toggleCustomRole()">
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ $teamMember->role === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Custom Role Name --}}
                    <div id="custom-role-section" style="display: {{ $teamMember->role === 'custom' ? 'block' : 'none' }};">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Custom Role Name</label>
                        <input type="text" name="custom_role_name" value="{{ old('custom_role_name', $teamMember->custom_role_name) }}"
                               class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                               placeholder="e.g., Technical Director">
                        @error('custom_role_name')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Permissions --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Permissions</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($permissions as $key => $label)
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition">
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                           {{ in_array($key, $teamMember->permissions ?? []) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-200">
                                    <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-4 pt-4">
                        <a href="{{ route('admin.events.team.index', $event->id) }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Team Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleCustomRole() {
            const roleSelect = document.getElementById('role');
            const customSection = document.getElementById('custom-role-section');
            
            if (roleSelect.value === 'custom') {
                customSection.style.display = 'block';
            } else {
                customSection.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', toggleCustomRole);
    </script>
</x-layouts.app.admin>