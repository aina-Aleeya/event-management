<div class="bg-gray-50 min-h-screen py-6">
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-100 text-blue-800 font-medium">
                            {{ $group->name }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-purple-100 text-purple-800 font-medium">
                            {{ $participants->first()['category'] ?? 'N/A' }}
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
                    <p class="text-sm text-blue-800">Enter scores for each round. Average calculated automatically in real-time. You can update scores anytime.</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="submit">
            <!-- Table -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                                    NO.
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Participant
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                    Round 1
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                    Round 2
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                    Round 3
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                    Remarks
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Average
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($participants as $index => $participant)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Number -->
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>

                                    <!-- Participant Name -->
                                    <td class="px-4 py-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $participant['nama_penuh'] }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $participant['category'] }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Round 1 -->
                                    <td class="px-4 py-3">
                                        <input 
                                            type="number" 
                                            wire:model.live="scores.{{ $participant['id'] }}.round1" 
                                            step="0.01" 
                                            min="0"
                                            max="100"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="0.00">
                                        @error('scores.'.$participant['id'].'.round1') 
                                            <span class="text-xs text-red-600">{{ $message }}</span> 
                                        @enderror
                                    </td>

                                    <!-- Round 2 -->
                                    <td class="px-4 py-3">
                                        <input 
                                            type="number" 
                                            wire:model.live="scores.{{ $participant['id'] }}.round2" 
                                            step="0.01" 
                                            min="0"
                                            max="100"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="0.00">
                                        @error('scores.'.$participant['id'].'.round2') 
                                            <span class="text-xs text-red-600">{{ $message }}</span> 
                                        @enderror
                                    </td>

                                    <!-- Round 3 -->
                                    <td class="px-4 py-3">
                                        <input 
                                            type="number" 
                                            wire:model.live="scores.{{ $participant['id'] }}.round3" 
                                            step="0.01" 
                                            min="0"
                                            max="100"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="0.00">
                                        @error('scores.'.$participant['id'].'.round3') 
                                            <span class="text-xs text-red-600">{{ $message }}</span> 
                                        @enderror
                                    </td>

                                    <!-- Remarks -->
                                    <td class="px-4 py-3">
                                        <input 
                                            type="text" 
                                            wire:model.defer="scores.{{ $participant['id'] }}.remarks"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                                            placeholder="Optional">
                                        @error('scores.'.$participant['id'].'.remarks') 
                                            <span class="text-xs text-red-600">{{ $message }}</span> 
                                        @enderror
                                    </td>

                                    <!-- Average (Real-time) -->
                                    <td class="px-4 py-3 text-center">
                                        <div class="text-lg font-bold text-gray-900">
                                            {{ number_format($this->calculateAverage($participant['id']), 2) }}
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-4 py-3 text-center">
                                        @if($participant['existing_score'])
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Submitted
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
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

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    
                    <!-- Icon when not loading -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    
                    <!-- Spinner when loading -->
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24" wire:loading>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <span wire:loading.remove>Submit All Scores</span>
                    <span wire:loading>Updating...</span>
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
</div>