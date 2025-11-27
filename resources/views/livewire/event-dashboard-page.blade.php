<x-layouts.app>
  <div class="max-w-7xl mx-auto px-6 py-6">

    <h2 class="text-xl font-semibold text-gray-800 mb-4">
      Dashboard - {{ $event->title }}
    </h2>


    <!-- SUMMARY CARDS -->
    <div class="grid gap-6 mb-8 grid-cols-1 md:grid-cols-3 xl:grid-cols-5">

      <!-- Total Participants -->
      <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
        <div class="flex justify-between items-start">
          <div class="text-[10px] font-semibold text-gray-700">Total Participants</div>
          <i class="fas fa-users text-gray-400 text-sm"></i>
        </div>
        <div class="text-xl font-bold text-red-700 leading-none">
          {{ number_format($totalParticipants ?? 0) }}
        </div>
      </div>

      <!-- Payments Completed -->
      <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
        <div class="flex justify-between items-start">
          <div class="text-[10px] font-semibold text-gray-700">Payments Completed</div>
          <i class="fa-regular fa-money-bill-1 text-gray-400 text-sm"></i>
        </div>
        <div class="text-xl font-bold text-red-700 leading-none">
          {{ number_format($completedPayments) }}
        </div>
      </div>

      <!-- Pending Payments -->
      <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
        <div class="flex justify-between items-start">
          <div class="text-[10px] font-semibold text-gray-700">Pending Payments</div>
          <i class="fa-regular fa-clock text-gray-400 text-sm"></i>
        </div>
        <div class="text-xl font-bold text-red-700 leading-none">
          {{ number_format($pendingPayments ?? 0) }}
        </div>
      </div>

      <!-- Total Revenue -->
      <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
        <div class="flex justify-between items-start">
          <div class="text-[10px] font-semibold text-gray-700">Total Revenue (RM)</div>
          <i class="fa-solid fa-chart-line text-gray-400 text-sm"></i>
        </div>
        <div class="text-xl font-bold text-red-700 leading-none">
          {{ number_format($totalRevenue ?? 0) }}
        </div>
      </div>

      <!-- Total Clicks -->
      <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
        <div class="flex justify-between items-start">
          <div class="text-[10px] font-semibold text-gray-700">Total Clicks</div>
          <i class="fa-solid fa-circle-check text-gray-400 text-sm"></i>
        </div>
        <div class="text-xl font-bold text-red-700 leading-none">
          {{ number_format($clickCount) }}
        </div>
      </div>
    </div>

    <!-- PARTICIPANT OVERVIEW -->
    <div class="bg-white rounded-xl shadow overflow-hidden mb-8">
      <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Participant Overview</h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
              <th class="px-4 py-3">Participant</th>
              <th class="px-4 py-3">Category</th>
              <th class="px-4 py-3">Unique ID</th>
              <th class="px-4 py-3">Payment</th>
              <th class="px-4 py-3">Date</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y">
            @forelse($latestParticipants as $p)
              <tr class="text-gray-700">
                <td class="px-4 py-3">
                  <a href="{{ route('organiser.participant.view', $p->id) }}"
                    class="flex items-center text-sm hover:opacity-80 transition">
                    @if($p->gambar)
                      <img class="w-9 h-9 mr-3 rounded-full object-cover" src="{{ asset('storage/' . $p->gambar) }}"
                        alt="{{ $p->nama_penuh }}">
                    @else
                      <div
                        class="w-9 h-9 mr-3 rounded-full bg-purple-200 flex items-center justify-center text-purple-700 font-bold">
                        {{ strtoupper(substr($p->nama_penuh, 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <p class="font-semibold">{{ $p->nama_penuh }}</p>
                      <p class="text-xs text-gray-600">Registered Participant</p>
                    </div>
                  </a>
                </td>

                <td class="px-4 py-3 text-sm">{{ $p->pivot->kategori ?? '-' }}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ $p->pivot->unique_id ?? '-' }}</td>
                <td class="px-4 py-3 text-xs">
                  @if($p->pivot->status_bayaran === 'complete')
                    <span class="px-2 py-1 text-green-700 bg-green-100 rounded-full font-semibold">Completed</span>
                  @else
                    <span class="px-2 py-1 text-orange-700 bg-orange-100 rounded-full font-semibold">Pending</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">{{ $p->pivot->created_at->format('d/m/Y') ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                  No participants have registered yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="text-center py-3 bg-gray-50">
        <a href="{{ route('organiser.participants', $event->id) }}"
          class="text-red-600 hover:text-red-800 text-sm font-semibold">
          Show all participants →
        </a>
      </div>
    </div>

    <!-- RANKING SECTION -->
    <div class="mb-10">

      <!-- Header -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
          🏆 Live Ranking Leaderboard
        </h2>

        <div class="mt-4 sm:mt-0 flex gap-3">
          <a href="{{ route('organiser.ranking.report', $event->id) }}"
            class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium text-sm rounded-lg shadow hover:from-red-600 hover:to-red-700 transition transform active:scale-95">
            + Fill Ranking
          </a>

          <a href="{{ route('organiser.event.leaderboard', $event->id) }}"
            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium text-sm rounded-lg hover:bg-gray-50 transition">
            Full Leaderboard
          </a>
        </div>
      </div>

      <!-- TWO CONTAINERS SIDE-BY-SIDE -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!--INDIVIDUAL CATEGORY-->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <h3 class="text-lg font-semibold mb-4">Individual</h3>

          @php
            $first = $topIndividual->firstWhere('ranking', 1);
            $second = $topIndividual->firstWhere('ranking', 2);
            $third = $topIndividual->firstWhere('ranking', 3);
          @endphp

          @if($topIndividual->isNotEmpty())
            <div class="flex items-end justify-center gap-4">

              {{-- SECOND PLACE --}}
              <div class="flex flex-col items-center flex-1 min-w-[30%]">
                @if($second)
                  <div class="relative mb-3 flex flex-col items-center">
                    <div
                      class="w-14 h-14 rounded-full border-4 border-gray-300 flex items-center justify-center bg-white shadow">
                      {{ strtoupper(substr($second->penyertaan->peserta->nama_penuh, 0, 2)) }}
                    </div>
                    <div
                      class="absolute -bottom-2 w-6 h-6 rounded-full bg-gray-400 text-white text-xs font-bold flex items-center justify-center border-2 border-white">
                      2
                    </div>
                  </div>
                  <div
                    class="w-full h-36 bg-gray-100 rounded-t-lg border-t border-x border-gray-300 flex items-center justify-center">
                    <p class="font-bold text-gray-800 text-center text-sm px-2">
                      {{ $second->penyertaan->peserta->nama_penuh }}
                    </p>
                  </div>
                @endif
              </div>

              {{-- FIRST PLACE --}}
              <div class="flex flex-col items-center flex-1 min-w-[30%]">
                @if($first)
                  <div class="relative mb-3 flex flex-col items-center">
                    <i class="fas fa-trophy text-yellow-500 text-xl mb-1"></i>
                    <div
                      class="w-14 h-14 rounded-full border-4 border-yellow-300 flex items-center justify-center bg-white shadow">
                      {{ strtoupper(substr($first->penyertaan->peserta->nama_penuh, 0, 2)) }}
                    </div>
                    <div
                      class="absolute -bottom-2 w-6 h-6 rounded-full bg-yellow-500 text-white text-xs font-bold flex items-center justify-center border-2 border-white">
                      1
                    </div>
                  </div>
                  <div
                    class="w-full h-48 bg-yellow-100 rounded-t-lg border-t border-x border-yellow-300 flex items-center justify-center">
                    <p class="font-bold text-gray-800 text-center text-sm px-2">
                      {{ $first->penyertaan->peserta->nama_penuh }}
                    </p>
                  </div>
                @endif
              </div>

              {{-- THIRD PLACE --}}
              <div class="flex flex-col items-center flex-1 min-w-[30%]">
                @if($third)
                  <div class="relative mb-3 flex flex-col items-center">
                    <div
                      class="w-14 h-14 rounded-full border-4 border-orange-300 flex items-center justify-center bg-white shadow">
                      {{ strtoupper(substr($third->penyertaan->peserta->nama_penuh, 0, 2)) }}
                    </div>
                    <div
                      class="absolute -bottom-2 w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center border-2 border-white">
                      3
                    </div>
                  </div>
                  <div
                    class="w-full h-28 bg-orange-100 rounded-t-lg border-t border-x border-orange-300 flex items-center justify-center">
                    <p class="font-bold text-gray-800 text-center text-sm px-2">
                      {{ $third->penyertaan->peserta->nama_penuh }}
                    </p>
                  </div>
                @endif
              </div>

            </div>
          @else
            <p class="text-gray-500">No ranking data yet.</p>
          @endif
        </div>

        <!-- GROUP CATEGORY -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <h3 class="text-lg font-semibold mb-4">Group</h3>

          @php
            $g1 = $topGroup->firstWhere('ranking', 1);
            $g2 = $topGroup->firstWhere('ranking', 2);
            $g3 = $topGroup->firstWhere('ranking', 3);
          @endphp

          @if($topGroup->isNotEmpty())
            <div class="flex items-end justify-center gap-4">

              {{-- SECOND PLACE --}}
              <div class="flex flex-col items-center flex-1 min-w-[30%]">
                @if($g2)
                  <div class="relative mb-3 flex flex-col items-center">
                    <div
                      class="w-14 h-14 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center shadow">
                      {{ strtoupper(substr($g2->group_name, 0, 2)) }}
                    </div>
                    <div
                      class="absolute -bottom-2 w-6 h-6 rounded-full bg-gray-400 text-white text-xs font-bold flex items-center justify-center border-2 border-white">
                      2
                    </div>
                  </div>
                  <div class="w-full h-36 bg-gray-100 rounded-t-lg border-t border-x border-gray-300 p-2 text-center">
                    <p class="font-bold text-sm">{{ $g2->group_name }}</p>
                    <p class="text-xs text-gray-500">{{ $g2->group_members }}</p>
                  </div>
                @endif
              </div>

              {{-- FIRST PLACE --}}
              <div class="flex flex-col items-center flex-1 min-w-[30%]">
                @if($g1)
                  <div class="relative mb-3 flex flex-col items-center">
                    <i class="fas fa-trophy text-yellow-500 text-xl mb-1"></i>
                    <div
                      class="w-14 h-14 rounded-full border-4 border-yellow-300 bg-white flex items-center justify-center shadow">
                      {{ strtoupper(substr($g1->group_name, 0, 2)) }}
                    </div>
                    <div
                      class="absolute -bottom-2 w-6 h-6 rounded-full bg-yellow-500 text-white text-xs font-bold flex items-center justify-center border-2 border-white">
                      1
                    </div>
                  </div>
                  <div class="w-full h-48 bg-yellow-100 rounded-t-lg border-t border-x border-yellow-300 p-2 text-center">
                    <p class="font-bold text-sm">{{ $g1->group_name }}</p>
                    <p class="text-xs text-gray-500">{{ $g1->group_members }}</p>
                  </div>
                @endif
              </div>

              {{-- THIRD PLACE --}}
              <div class="flex flex-col items-center flex-1 min-w-[30%]">
                @if($g3)
                  <div class="relative mb-3 flex flex-col items-center">
                    <div
                      class="w-14 h-14 rounded-full border-4 border-orange-300 bg-white flex items-center justify-center shadow">
                      {{ strtoupper(substr($g3->group_name, 0, 2)) }}
                    </div>
                    <div
                      class="absolute -bottom-2 w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center border-2 border-white">
                      3
                    </div>
                  </div>
                  <div class="w-full h-28 bg-orange-100 rounded-t-lg border-t border-x border-orange-300 p-2 text-center">
                    <p class="font-bold text-sm">{{ $g3->group_name }}</p>
                    <p class="text-xs text-gray-500">{{ $g3->group_members }}</p>
                  </div>
                @endif
              </div>

            </div>
          @else
            <p class="text-gray-500">No ranking data yet.</p>
          @endif
        </div>

      </div>

    </div>


</x-layouts.app>