    <div class="px-6 lg:px-12 py-8">

        <h2 class="text-2xl font-bold mb-6">Ranking Report: {{ $event->nama_event }}</h2>

        {{-- Category Switch --}}
        <div class="mb-6 flex items-center gap-4">
            <label class="font-semibold">Category:</label>
            <select wire:model="category" wire:change="categoryChanged" class="border rounded px-3 py-2 w-48">
                <option value="Individu">Individu</option>
                <option value="Berkumpulan">Berkumpulan</option>
            </select>

        </div>

        {{-- Ranking Slots 1,2,3 --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ([1,2,3] as $slot)
                <div class="border p-6 rounded-lg shadow-lg bg-gray-50">
                    <h3 class="font-bold mb-4 text-left text-lg">Ranking {{ $slot }}</h3>

                    {{-- Search Box --}}
                    <input 
                        type="text"
                        wire:model.live="search.{{ $slot }}"
                        placeholder="Search name or group..."
                        class="w-full border px-3 py-2 rounded mb-3"
                    >

                    {{-- Suggestion Dropdown --}}
                    @if(!empty($suggestions[$slot]))
                        <div class="border bg-white rounded shadow max-h-40 overflow-y-auto mb-3">
                            @foreach($suggestions[$slot] as $s)
                                <div 
                                    wire:click="selectSuggestion({{ $s['id'] }}, {{ $slot }})"
                                    class="px-4 py-2 hover:bg-gray-200 cursor-pointer"
                                >
                                    {{ $s['name'] }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Selected name --}}
                    @if(isset($selectedNames[$slot]) && $selectedNames[$slot])
                        <div class="mt-3 p-3 bg-green-100 rounded">
                            <strong>Selected:</strong><br>
                            {{ $selectedNames[$slot] }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Buttons --}}
        <div class="mt-8 flex gap-4">
            <button wire:click="save" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                Save Ranking
            </button>

            <button wire:click="resetRanking" class="bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700 transition">
                Reset Ranking
            </button>
        </div>

        {{-- Flash Message --}}
        @if(session()->has('success'))
            <div class="mt-4 p-3 bg-green-200 border border-green-400 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- MINI LEADERBOARD --}}
        <div class="mt-10">
            <h3 class="text-xl font-bold mb-4">Mini Leaderboard</h3>

            @if($miniLeaderboard->isEmpty())
                <p>No rankings saved yet.</p>
            @else
                <div class="overflow-x-auto">
                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="border px-4 py-2 text-left">Rank</th>
                            <th class="border px-4 py-2 text-left">Name / Group</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($miniLeaderboard as $item)
                            <tr class="border">
                                <td class="border px-4 py-2">{{ $item->ranking }}</td>
                                <td class="border px-4 py-2">
                                    @if($category === 'Berkumpulan' && isset($item->group))
                                        <strong>{{ $item->group->name }}</strong><br>
                                        <ul class="ml-4 list-disc">
                                            @foreach($item->namaAhli as $nama)
                                                <li>{{ $nama }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ $item->penyertaan->peserta->nama_penuh }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                </div>
            @endif
        </div>

    </div>
