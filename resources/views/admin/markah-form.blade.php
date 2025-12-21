<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Scores - {{ $group->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen py-6">
    <div class="max-w-6xl mx-auto px-4">

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Header Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-5 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h1>
                    <div class="flex items-center gap-2 text-sm">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-100 text-blue-800 font-medium">
                            {{ $group->name }}
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-purple-100 text-purple-800 font-medium">
                            {{ $participants->first()->category ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            @if($event->start_date || $event->venue)
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex gap-6 text-sm text-gray-600">
                        @if($event->start_date)
                            <div><span class="font-medium">Date:</span> {{ $event->start_date->format('d M Y') }}</div>
                        @endif
                        @if($event->venue)
                            <div><span class="font-medium">Venue:</span> {{ $event->venue }}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-blue-900 mb-1">Quick Instructions</h3>
                    <p class="text-sm text-blue-800">Enter scores for each round. Average calculated automatically. You
                        can update scores anytime.</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('markah.submit', $token) }}">
            @csrf

            <!-- Compact Table View -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                                    NO.</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Participant</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                    Round 1</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                    Round 2</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                    Round 3</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                    Remarks</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($participants as $index => $participant)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="hidden" name="scores[{{ $index }}][peserta_id]"
                                            value="{{ $participant->id }}">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $participant->nama_penuh }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $participant->category }}</div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="number" name="scores[{{ $index }}][round1]" step="0.01" min="0"
                                            value="{{ $participant->existing_score->round1 ?? '' }}"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="0.00">
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="number" name="scores[{{ $index }}][round2]" step="0.01" min="0"
                                            value="{{ $participant->existing_score->round2 ?? '' }}"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="0.00">
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="number" name="scores[{{ $index }}][round3]" step="0.01" min="0"
                                            value="{{ $participant->existing_score->round3 ?? '' }}"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="0.00">
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="text" name="scores[{{ $index }}][remarks]"
                                            value="{{ $participant->existing_score->remarks ?? '' }}"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="Optional">
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        @if($participant->existing_score)
                                            <div>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Submitted
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Avg: <span
                                                        class="font-medium text-gray-900">{{ number_format($participant->existing_score->average, 2) }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-6">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-red-900 mb-2">Please fix the following errors:</h3>
                            <ul class="list-disc list-inside text-sm text-red-800 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Submit All Scores
                </button>
            </div>
        </form>

        <!-- Footer Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Need help? Contact the event organizer for assistance.
            </p>
        </div>
    </div>
</body>

</html>