<x-layouts.app.admin>
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Event Approval</h2>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if ($pendingEvents->isEmpty())
        <p>No pending events.</p>
    @else
        <table class="w-full shadow-sm rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="p-3 text-left">Title</th>
                    <th class="p-3 text-left">Submitted By</th>
                    <th class="p-3 text-left">Approve</th>
                    <th class="p-3 text-left">Reject</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pendingEvents as $event)
                    <tr class="border-t">
                        <td class="p-3 text-left">{{ $event->title }}</td>
                        <td class="p-3 text-left">{{ $event->user->name ?? 'Unknown' }}</td>

                        <td class="p-3 text-left">
                            <button wire:click="approveEvent({{ $event->id }})"
                                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                Approve
                            </button>
                        </td>

                        <td class="p-3 text-left">
                            <input type="text" wire:model.defer="reason.{{ $event->id }}" placeholder="Enter reject reason"
                                class="border p-1 rounded mb-1">

                            <button wire:click="rejectEvent({{ $event->id }})"
                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                Reject
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</x-layouts.app.admin>
