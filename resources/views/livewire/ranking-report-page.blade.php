<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">
        Fill Ranking for Event: {{ $event->title ?? '' }}
    </h2>

    <!-- Category -->
    <div class="mb-6">
        <label class="font-semibold">Category:</label>
        <select wire:model="category" class="border px-2 py-1 rounded">
            <option value="">Select Category</option>
            <option value="Individu">Individu</option>
            <option value="Kumpulan">Kumpulan</option>
        </select>
    </div>

    <!-- Rankings -->
    @foreach([1,2,3] as $slot)
        <div class="mb-6">
            <label class="font-semibold block mb-1">Ranking {{ $slot }}:</label>
            <input 
                type="text" 
                wire:model.debounce.300ms="search.{{ $slot }}"
                placeholder="Type participant or group name" 
                class="border px-2 py-1 w-full rounded"
            />

            @if(!empty($suggestions[$slot]))
                <ul class="border rounded bg-white mt-1 max-h-48 overflow-y-auto">
                    @foreach($suggestions[$slot] as $s)
                        <li 
                            wire:click="selectSuggestion({{ $s['id'] }}, {{ $slot }})"
                            class="p-2 hover:bg-gray-200 cursor-pointer"
                        >
                            {{ $s['name'] }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if(isset($selectedNames[$slot]))
                <p class="text-sm text-gray-600 mt-1">
                    Selected: <strong>{{ $selectedNames[$slot] }}</strong>
                </p>
            @endif
        </div>
    @endforeach

    <button 
        wire:click="save" 
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
    >
        Save Ranking
    </button>

    @if(session()->has('success'))
        <p class="text-green-600 mt-3">{{ session('success') }}</p>
    @endif

    <!-- Mini Leaderboard -->
    @if(count($miniLeaderboard))
        <div class="mt-8">
            <h3 class="font-bold text-lg mb-2">Mini Leaderboard</h3>
            <table class="w-full border border-gray-300 rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Ranking</th>
                        <th class="p-2 border">Participant / Group</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($miniLeaderboard as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border text-center">{{ $r->ranking }}</td>
                            <td class="p-2 border">
                                {{ $r->penyertaan->peserta->nama_penuh ?? 'Group: ' . ($r->penyertaan->group_token ?? 'Unknown') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
