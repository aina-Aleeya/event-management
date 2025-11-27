<div class="max-w-4xl mx-auto px-4 py-10">
    <!-- Title -->
    <h1 class="text-3xl font-bold mb-6 text-center">
        🏆 Leaderboard — {{ $event->title }}
    </h1>

    <!-- Category Switch -->
    <div class="flex justify-center mb-6">
        <div class="bg-gray-200 rounded-lg p-1 flex space-x-1">
            <button wire:click="$set('category', 'Individu')" class="px-4 py-2 rounded-md text-sm font-semibold 
            {{ $category === 'Individu' ? 'bg-red-500 text-white' : 'text-gray-700 hover:bg-gray-300' }}">
                Individu
            </button>
            <button wire:click="$set('category', 'Berkumpulan')" class="px-4 py-2 rounded-md text-sm font-semibold 
            {{ $category === 'Berkumpulan' ? 'bg-red-500 text-white' : 'text-gray-700 hover:bg-gray-300' }}">
                Berkumpulan
            </button>
        </div>
    </div>

    <!-- TOP 3 PODIUM -->
    <div class="flex justify-center items-end gap-4 my-10">

        @foreach ($topThree as $index => $r)
            @php
                $rank = $index + 1;

                $styles = [
                    1 => ['h' => 'h-40', 'color' => 'bg-yellow-400', 'label' => '1st'],
                    2 => ['h' => 'h-32', 'color' => 'bg-gray-400', 'label' => '2nd'],
                    3 => ['h' => 'h-28', 'color' => 'bg-orange-500', 'label' => '3rd'],
                ];
            @endphp

            <div class="flex flex-col items-center">
                <!-- Avatar -->
                <div class="mb-3">
                    <div class="w-20 h-20 rounded-full ring-4 ring-white overflow-hidden shadow">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($category === 'Individu' ? $r->penyertaan->peserta->nama_penuh : $r->group_name) }}&background=random"
                            class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Name -->
                <p class="text-center font-semibold text-gray-800 w-28 truncate">
                    @if ($category === 'Individu')
                        {{ $r->penyertaan->peserta->nama_penuh }}
                    @else
                        {{ $r->group_name }}
                    @endif
                </p>

                @if ($category === 'Berkumpulan')
                    <p class="text-xs text-gray-500 text-center w-32">
                        {{ $r->group_members }}
                    </p>
                @endif

                <!-- Pillar -->
                <div
                    class="{{ $styles[$rank]['h'] }} w-20 mt-4 {{ $styles[$rank]['color'] }} rounded-t-lg flex justify-center items-start pt-3 shadow">
                    <span class="text-white text-xl font-bold opacity-60">
                        {{ $styles[$rank]['label'] }}
                    </span>
                </div>
            </div>

        @endforeach
    </div>

    <!-- FULL RANK LIST -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-bold mb-4">
            Ranking List — {{ $category }}
        </h2>

        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-2 w-16">Rank</th>
                    <th class="p-2">Name</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rankList as $r)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-bold">#{{ $r->ranking }}</td>

                        <td class="p-3">
                            @if ($category === 'Individu')
                                {{ $r->penyertaan->peserta->nama_penuh }}
                            @else
                                <div class="font-semibold">{{ $r->group_name }}</div>
                                <div class="text-xs text-gray-500">{{ $r->group_members }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center p-4 text-gray-500">
                            No ranking data yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>