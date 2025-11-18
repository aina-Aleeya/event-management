<div>
  <x-layouts.app>
    <div class="max-w-7xl mx-auto px-6 py-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Dashboard - {{ $event->title }} </h2>

      <!-- Summary Cards -->
      <div class="grid gap-6 mb-8 grid-cols-1 md:grid-cols-3 xl:grid-cols-5">

        <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
          <div class="flex justify-between items-start">
            <div class="text-[10px] font-semibold text-gray-700">Total Participants</div>
            <div class="text-red-600">
              <i class="fas fa-users text-gray-400 text-sm"></i>
            </div>
          </div>
          <div class="text-xl font-bold text-red-700 leading-none">{{ number_format($totalParticipants ?? 0) }}</div>
          <div class="text-[10px] text-gray-500">&nbsp;</div>
        </div>

        <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
          <div class="flex justify-between items-start">
            <div class="text-[10px] font-semibold text-gray-700">Payments Completed</div>
            <div class="text-red-600">
              <i class="fa-regular fa-money-bill-1 text-gray-400 text-sm"></i>
            </div>
          </div>
          <div class="text-xl font-bold text-red-700 leading-none">{{ number_format($completedPayments)}}</div>
          <div class="text-[10px] text-gray-500">&nbsp;</div>
        </div>

        <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
          <div class="flex justify-between items-start">
            <div class="text-[10px] font-semibold text-gray-700">Pending Payments</div>
            <div class="text-red-600">
              <i class="fa-regular fa-clock text-gray-400 text-sm"></i>
            </div>
          </div>
          <div class="text-xl font-bold text-red-700 leading-none">{{ number_format($pendingPayments ?? 0) }}</div>
          <div class="text-[10px] text-gray-500">&nbsp;</div>
        </div>

        <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
          <div class="flex justify-between items-start">
            <div class="text-[10px] font-semibold text-gray-700">Total Revenue (RM)</div>
            <div class="text-red-600">
              <i class="fa-solid fa-chart-line text-gray-400 text-sm"></i>
            </div>
          </div>
          <div class="text-xl font-bold text-red-700 leading-none">{{ number_format($totalRevenue ?? 0) }}</div>
          <div class="text-[10px] text-gray-500">&nbsp;</div>
        </div>


        <div class="bg-white shadow-lg rounded-lg h-24 flex flex-col justify-between p-3 border-t-4 border-red-600">
          <div class="flex justify-between items-start">
            <div class="text-[10px] font-semibold text-gray-700">Total Clicks</div>
            <div class="text-red-600">
              <i class="fa-solid fa-circle-check text-gray-400 text-sm"></i>
            </div>
          </div>
          <div class="text-xl font-bold text-red-700 leading-none">{{ number_format($clickCount)}}</div>
          <div class="text-[10px] text-gray-500">&nbsp;</div>
        </div>

      </div>

      <!-- Overview + Ranking (Side by Side) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Participant Overview -->
        <div class="bg-white rounded-xl shadow overflow-hidden lg:col-span-2">
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
                @forelse($participants->take(5) as $p)
                  <tr class="text-gray-700">
                    <td class="px-4 py-3">
                      <a href="{{ route('admin.participant.view', $p->id) }}"
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

                    <td class="px-4 py-3 text-sm">{{ $p->pivot->kategori_nama ?? '-' }}</td>

                    <td class="px-4 py-3 text-sm font-medium text-gray-700">
                      {{ $p->pivot->unique_id ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-xs">
                      @if(isset($p->pivot) && $p->pivot->status_bayaran === 'complete')
                        <span class="px-2 py-1 text-green-700 bg-green-100 rounded-full font-semibold">Completed</span>
                      @elseif(isset($p->pivot))
                        <span class="px-2 py-1 text-orange-700 bg-orange-100 rounded-full font-semibold">Pending</span>
                      @else
                        <span class="px-2 py-1 text-gray-500 bg-gray-100 rounded-full font-semibold">N/A</span>
                      @endif
                    </td>

                    <td class="px-4 py-3 text-sm">
                      @if(isset($p->pivot) && $p->pivot->created_at)
                        {{ $p->pivot->created_at->format('d/m/Y') }}
                      @else
                        -
                      @endif
                    </td>
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

          <div class="bg-body-tertiary p-0 card-footer mt-4 text-center">
            <a href="{{ route('admin.participants', $event->id) }}"
              class="w-full py-2 btn btn-link btn-sm text-blue-600 hover:text-blue-800 flex justify-center items-center gap-1">
              Show all participants
              <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                <path fill="currentColor" d="M310.6 233.4c12.5 12.5..."></path>
              </svg>
            </a>
          </div>
        </div>

        <!-- Top 3 Ranking -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
          <div>
            <h2 class="text-xl font-semibold mb-4">🏆 Top 3 Ranking</h2>

            @if($topRankings->isNotEmpty())
              <table class="w-full text-left border-t">
                <thead class="bg-gray-100 text-sm uppercase text-gray-600">
                  <tr>
                    <th class="p-3">Rank</th>
                    <th class="p-3">Name</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($topRankings as $index => $r)
                    <tr class="border-b hover:bg-gray-50">
                      <td class="p-3 font-bold">#{{ $index + 1 }}</td>
                      <td class="p-3">{{ $r->penyertaan->peserta->nama_penuh ?? 'Unknown' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <p class="text-gray-500">No ranking data yet.</p>
            @endif
          </div>

          <!-- View Leaderboard Link -->
          <div class="bg-body-tertiary p-0 card-footer mt-4 text-center">
            <a href="{{ route('admin.event.leaderboard', $event->id) }}"
              class="w-full py-2 btn btn-link btn-sm text-blue-600 hover:text-blue-800 flex justify-center items-center gap-1">
              View Full Leaderboard →
              <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                <path fill="currentColor" d="M310.6 233.4c12.5 12.5..."></path>
              </svg>
            </a>
          </div>
        </div>



        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
          <a href="{{ route('admin.ranking.report', $event->id) }}"
            class="px-4 py-2 bg-red-400 text-white rounded-lg hover:bg-red-500">Fill Ranking</a>
        </div>
      </div>
  </x-layouts.app>
</div>