<div class="space-y-6">
    @foreach($groups as $group)
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">{{ $group['name'] }} ({{ $group['capacity'] ?? 'No limit' }})</h3>
            </div>

            {{-- Participant Table --}}
            <div class="overflow-x-auto mb-2">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <tr>
                            <th class="p-3">Name</th>
                            <th class="p-3">Category</th>
                            <th class="p-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['pesertas'] as $p)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="p-3">{{ $p['nama_penuh'] }}</td>
                                <td class="p-3">{{ $p['category'] }}</td>
                                <td class="p-3">
                                    <button wire:click="unassign({{ $p['id'] }}, {{ $group['id'] }})"
                                        class="text-red-500 px-2 py-1 rounded hover:bg-red-100">Unassign</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Assign Participant --}}
            <div class="flex gap-2 items-center mt-2">
                <select wire:model="selectedPeserta.{{ $group['id'] }}" class="border rounded px-2 py-1">
                    <option value="">--Select participant--</option>
                    @foreach($participants as $p)
                        <option value="{{ $p['id'] }}">{{ $p['nama_penuh'] }} ({{ $p['category'] }})</option>
                    @endforeach
                </select>
                <button wire:click="assign({{ $group['id'] }})"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Assign</button>
            </div>
        </div>
    @endforeach
</div>
