<x-layouts.app.admin>
    <x-slot name="breadcrumbs">
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 transition">Events</a>
        </li>
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('organiser.events.team.index', $event->id) }}"
                class="text-gray-600 hover:text-purple-600 transition">Team</a>
        </li>
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-900 font-medium">Invite Member</span>
        </li>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto px-6 py-8">

            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h2
                        class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Invite Team Member
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $event->title }}</p>
                </div>
                <a href="{{ route('organiser.events.team.index', $event->id) }}"
                    class="px-5 py-2.5 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-77h18" />
                    </svg>
                    Back to Team
                </a>
            </div>
            {{-- Info Banner --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 text-sm">Invitation Email</h4>
                        <p class="text-blue-700 text-sm mt-1">
                            An invitation email will be sent to the member with a link to set up their account and join
                            your event team.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <form method="POST" action="{{ route('organiser.events.team.store', $event->id) }}" class="space-y-6"
                    id="teamMemberForm">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                            autofocus
                            class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            placeholder="Enter member's full name">
                        @error('name')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            placeholder="member@example.com">
                        <p class="text-xs text-gray-500 mt-1">The invitation will be sent to this email address</p>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Role Selection --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role_id" id="role" required
                            class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            onchange="handleRoleChange()">
                            <option value="">-- Select Role --</option>

                            {{-- System Roles --}}
                            @if ($systemRoles->count() > 0)
                                <optgroup label="Standard Roles">
                                    @foreach ($systemRoles as $role)
                                        <option value="{{ $role->id }}"
                                            data-permissions='@json($role->permissions)'
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if ($role->description)
                                                - {{ Str::limit($role->description, 40) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            {{-- Custom Roles --}}
                            @if ($customRoles->count() > 0)
                                <optgroup label="Your Custom Roles">
                                    @foreach ($customRoles as $role)
                                        <option value="{{ $role->id }}"
                                            data-permissions='@json($role->permissions)'
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if ($role->description)
                                                - {{ Str::limit($role->description, 40) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            {{-- Custom Role Option --}}
                            <option value="custom" {{ old('role_id') == 'custom' ? 'selected' : '' }}>
                                + Create New Custom Role
                            </option>
                        </select>
                        @error('role_id')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Custom Role Section --}}
                    <div id="custom-role-section" style="display: none;"
                        class="space-y-4 p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg border-2 border-purple-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <h4 class="font-semibold text-purple-900">Create Custom Role</h4>
                        </div>

                        {{-- Custom Role Name --}}
                        <div>
                            <label for="custom_role_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="custom_role_name" name="custom_role_name"
                                value="{{ old('custom_role_name') }}"
                                class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                placeholder="e.g., Technical Director, Marketing Lead">
                            @error('custom_role_name')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Custom Role Description --}}
                        <div>
                            <label for="custom_role_description"
                                class="block text-sm font-semibold text-gray-700 mb-2">
                                Description (Optional)
                            </label>
                            <textarea id="custom_role_description" name="custom_role_description" rows="2"
                                class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition resize-none"
                                placeholder="Brief description of this role's responsibilities">{{ old('custom_role_description') }}</textarea>
                            @error('custom_role_description')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Custom Role Permissions --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Permissions <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach ($allPermissions as $key => $label)
                                    <label
                                        class="flex items-start p-3 border border-purple-200 bg-white rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                        <input type="checkbox" name="custom_role_permissions[]"
                                            value="{{ $key }}"
                                            class="custom-permission-checkbox mt-0.5 w-4 h-4 text-purple-600 rounded focus:ring-2 focus:ring-purple-200"
                                            {{ is_array(old('custom_role_permissions')) && in_array($key, old('custom_role_permissions')) ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                            @if ($key === 'manage_participants')
                                                <p class="text-xs text-gray-500 mt-0.5">Add, edit, and remove
                                                    participants</p>
                                            @elseif($key === 'manage_groups')
                                                <p class="text-xs text-gray-500 mt-0.5">Create and organize participant
                                                    groups</p>
                                            @elseif($key === 'view_reports')
                                                <p class="text-xs text-gray-500 mt-0.5">Access event analytics and
                                                    reports</p>
                                            @elseif($key === 'manage_scores')
                                                <p class="text-xs text-gray-500 mt-0.5">Enter and update scores/results
                                                </p>
                                            @elseif($key === 'send_notifications')
                                                <p class="text-xs text-gray-500 mt-0.5">Send emails and notifications
                                                </p>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('custom_role_permissions')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <p class="text-xs text-purple-700 flex items-start gap-1">
                            <svg class="w-3.5 h-3.5 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            This custom role will be saved and available for future use in all your events
                        </p>
                    </div>

                    {{-- Role Permissions Preview (for existing roles) --}}
                    <div id="role-permissions-preview" style="display: none;"
                        class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-semibold text-sm text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Permissions for this role:
                        </h4>
                        <div id="permissions-list" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <!-- Permissions will be dynamically inserted here -->
                        </div>
                    </div>

                    {{-- Personal Message --}}
                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                            Personal Message (Optional)
                        </label>
                        <textarea id="message" name="message" rows="4"
                            class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition resize-none"
                            placeholder="Add a personal message to include in the invitation email...">{{ old('message') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">This message will be included in the invitation email</p>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('organiser.events.team.index', $event->id) }}"
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold text-center">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Send Invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Permission labels for display
        const permissionLabels = @json($allPermissions);

        function handleRoleChange() {
            const roleSelect = document.getElementById('role');
            const customSection = document.getElementById('custom-role-section');
            const customRoleInput = document.getElementById('custom_role_name');
            const permissionsPreview = document.getElementById('role-permissions-preview');
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];

            if (roleSelect.value === 'custom') {
                // Show custom role section
                customSection.style.display = 'block';
                customRoleInput.required = true;
                permissionsPreview.style.display = 'none';

                // Make role_id not required when custom is selected
                roleSelect.removeAttribute('name');

            } else if (roleSelect.value === '') {
                // Nothing selected
                customSection.style.display = 'none';
                customRoleInput.required = false;
                permissionsPreview.style.display = 'none';
                roleSelect.setAttribute('name', 'role_id');

            } else {
                // Existing role selected
                customSection.style.display = 'none';
                customRoleInput.required = false;
                roleSelect.setAttribute('name', 'role_id');

                // Show permissions preview
                const permissions = JSON.parse(selectedOption.dataset.permissions || '[]');
                displayPermissionsPreview(permissions);
            }
        }

        function displayPermissionsPreview(permissions) {
            const preview = document.getElementById('role-permissions-preview');
            const list = document.getElementById('permissions-list');

            if (permissions.length === 0) {
                preview.style.display = 'none';
                return;
            }

            // Build permissions HTML
            let html = '';
            permissions.forEach(perm => {
                const label = permissionLabels[perm] || perm;
                html += `
                <div class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>${label}</span>
                </div>
            `;
            });

            list.innerHTML = html;
            preview.style.display = 'block';
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            handleRoleChange();
        });

        // Form validation for custom role
        document.getElementById('teamMemberForm').addEventListener('submit', function(e) {
            const roleSelect = document.getElementById('role');

            if (roleSelect.value === 'custom') {
                const customRoleName = document.getElementById('custom_role_name').value;
                const customPermissions = document.querySelectorAll('.custom-permission-checkbox:checked');

                if (!customRoleName.trim()) {
                    e.preventDefault();
                    alert('Please enter a name for the custom role');
                    document.getElementById('custom_role_name').focus();
                    return;
                }

                if (customPermissions.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one permission for the custom role');
                    return;
                }
            }
        });
    </script>
</x-layouts.app.admin>
