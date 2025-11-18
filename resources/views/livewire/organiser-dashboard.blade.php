<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Organiser Dashboard</h1>
        <a href="{{ route('create-event') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Create
            Event</a>
    </div>
    <div class="max-w-7xl mx-auto p-6">

        {{-- ✅ Filter Dropdown (LIVEWIRE Version) --}}
        <div class="mb-3 w-1/4">
            <select wire:model="status" class="w-full border p-2 rounded">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>


        {{-- ✅ Flash messages --}}
        @if (session('success'))
            <div class="bg-green-200 text-green-700 p-2 rounded mb-2">{{ session('success') }}</div>
        @endif


        {{-- ✅ Table --}}
        @if ($events->isEmpty())
            <p>No events available.</p>
        @else
            <table class="w-full bg-white shadow rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-sm text-left">
                    <tr>
                        <th class="p-3">Event Name</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Rejection Reason</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($events as $event)
                        <tr class="border-b">
                            <td class="p-3 font-semibold">
                                <a>
                                    {{ $event->title }}
                                </a>
                            </td>

                            <td class="p-3">
                                @if ($event->status?->status === 'pending')
                                    <span class="px-2 py-1 rounded text-xs bg-yellow-200 text-yellow-700">Pending</span>
                                @elseif($event->status?->status === 'approved')
                                    <span class="px-2 py-1 rounded text-xs bg-green-200 text-green-700">Approved</span>
                                @elseif($event->status?->status === 'rejected')
                                    <span class="px-2 py-1 rounded text-xs bg-red-200 text-red-700">Rejected</span>
                                @endif
                            </td>

                            <td class="p-3">
                                {{ $event->status?->rejection_reason ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{-- ✅ Allow editing for pending or rejected events --}}
                                @if (in_array($event->status?->status, ['pending', 'rejected']))
                                    <a href="{{ route('event.edit', $event->id) }}"
                                        class="text-blue-600 hover:underline text-sm">
                                        Edit
                                    </a>
                                @elseif($event->status?->status === 'approved')
                                    <span class="text-gray-400 text-sm cursor-not-allowed">Edit</span>
                                @endif

                                {{-- ✅ Delete --}}
                                <button
                                    onclick="if (!confirm('Are you sure you want to delete this event?')) { event.stopImmediatePropagation(); return false; }"
                                    wire:click="deleteEvent({{ $event->id }})"
                                    class="text-red-600 hover:underline text-sm ml-2">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- ✅ Pagination --}}
            <div class="mt-4">
                {{-- {{ $events->links() }} --}}
            </div>
        @endif
    </div>

</div>
