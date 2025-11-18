<div class="p-6 bg-white min-h-screen">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
        Leaderboard {{ $event->title ?? '' }}
    </h2>

    <!-- Podium Top 3 -->
    <div class="flex items-end justify-center gap-4 mb-10">
        @php

            $first = $topThree[0] ?? null;
            $second = $topThree[1] ?? null;
            $third = $topThree[2] ?? null;

            $podium = [
                ['rank' => 2, 'data' => $second],
                ['rank' => 1, 'data' => $first],
                ['rank' => 3, 'data' => $third],
            ];
        @endphp

        @forelse($podium as $p)
            @php
                $r = $p['data'];
                $rank = $p['rank'];
                if (!$r)
                    continue;

                $peserta = $r->penyertaan->peserta ?? null;
                $style = $this->getRankStyle($rank);
            @endphp

            <div class="flex flex-col items-center w-1/3 px-1">
                <div class="relative mb-2">
                    <img src="{{ $peserta && $peserta->gambar
            ? asset('storage/' . $peserta->gambar)
            : 'https://api.dicebear.com/8.x/adventurer/svg?seed=' . ($peserta->nama_penuh ?? 'unknown') }}"
                        alt="{{ $peserta->nama_penuh ?? 'Unknown' }}"
                        class="rounded-full object-cover border-4 border-[#0F101A] ring-4 {{ $style['ring'] }} shadow-lg {{ $style['avatar'] }}" />
                    @if($rank == 1)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 text-yellow-400 text-3xl transform rotate-12">
                        </div>
                    @endif
                </div>

                <p class="font-bold {{ $style['nameText'] }} truncate max-w-full text-gray-800 text-center">
                    {{ $peserta->nama_penuh ?? $r->penyertaan->group_token ?? 'Unknown' }}
                </p>

                <div
                    class="relative w-full {{ $style['height'] }} {{ $style['bg'] }} rounded-t-lg mt-3 flex items-center justify-center border-b-8 {{ $style['border'] }} shadow-2xl">
                    <span class="text-5xl md:text-6xl font-black text-white/50">{{ $rank }}</span>
                </div>
            </div>

        @empty
            <div class="text-gray-800 font-bold text-center w-full">
                No ranking at this moment
            </div>
        @endforelse

    </div>

    <!-- All Participants (excluding top 3) -->
    <div class="bg-gray-50 rounded-lg overflow-hidden shadow">
        @foreach($allParticipants as $p)
            @if(!$p->rankingReport || $p->rankingReport->ranking > 3)
                <div class="flex items-center p-3 border-b border-gray-200 hover:bg-gray-100 transition">
                    <div class="w-10 text-center font-bold text-gray-800">
                        {{ $p->rankingReport->ranking ?? '-' }}
                    </div>
                    <img src="{{ $p->peserta && $p->peserta->gambar ? asset('storage/' . $p->peserta->gambar) : 'https://api.dicebear.com/8.x/adventurer/svg?seed=unknown' }}"
                        alt="{{ $p->peserta->nama_penuh ?? $p->group_token ?? 'Unknown' }}"
                        class="w-12 h-12 rounded-full border-2 border-gray-400 mx-3" />
                    <div class="text-gray-800 font-semibold">
                        {{ $p->peserta->nama_penuh ?? $p->group_token ?? 'Unknown' }}
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>