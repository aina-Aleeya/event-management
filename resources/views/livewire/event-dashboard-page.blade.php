<div>
  <x-layouts.app>
    <div class="max-w-6xl mx-auto px-6 py-10">
      <h1 class="text-2xl font-semibold mb-6">{{ $event->title }} — Dashboard</h1>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-purple-100 p-6 rounded-xl text-center shadow">
          <p class="text-sm text-gray-600">Total Participants</p>
          <p class="text-2xl font-bold text-purple-700">{{ $totalParticipants }}</p>
        </div>

        <div class="bg-green-100 p-6 rounded-xl text-center shadow">
          <p class="text-sm text-gray-600">Payments Completed</p>
          <p class="text-2xl font-bold text-green-700">{{ $completedPayments }}</p>
        </div>

        <div class="bg-yellow-100 p-6 rounded-xl text-center shadow">
          <p class="text-sm text-gray-600">Pending Payments</p>
          <p class="text-2xl font-bold text-yellow-700">{{ $pendingPayments }}</p>
        </div>

        <div class="bg-blue-100 p-6 rounded-xl text-center shadow">
          <p class="text-sm text-gray-600">Total Revenue (RM)</p>
          <p class="text-2xl font-bold text-blue-700">{{ $totalRevenue }}</p>
        </div>
      </div>

      <!-- Top 3 Ranking -->
      <div class="bg-white rounded-xl shadow p-6 mb-8">
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
                  <td class="p-3 font-bold text-purple-600">#{{ $index + 1 }}</td>
                  <td class="p-3">{{ $r->penyertaan->peserta->nama_penuh ?? 'Unknown' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <p class="text-gray-500">No ranking data yet.</p>
        @endif
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.participants', $event->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">View Details</a>
        <a href="{{ route('admin.ranking.report', $event->id) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Fill Ranking</a>
        <a href="{{ route('admin.event.leaderboard', $event->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">View Leaderboard</a>
      </div>
                  <div class="mt-6">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition">
                    ← Back
                </a>
            </div>
    </div>
  </x-layouts.app>
</div>
