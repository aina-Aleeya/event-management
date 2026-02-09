<div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
    
    {{-- Header --}}
    <div class="bg-gradient-to-r from-gray-800 to-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white mb-1">Confirm Participants</h2>
                <p class="text-gray-300 text-sm">
                    <i class="fa-solid fa-users mr-1"></i>
                    {{ $registrations->count() }} Participant{{ $registrations->count() > 1 ? 's' : '' }}
                </p>
            </div>
            <button wire:click="addMember"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                <i class="fa-solid fa-user-plus"></i>
                Add Member
            </button>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-6">
        @if($registrations->isEmpty())
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <i class="fa-solid fa-user-slash text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Participants Yet</h3>
                <p class="text-gray-600 mb-6">Add participants to continue with payment</p>
                <button wire:click="addMember"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                    <i class="fa-solid fa-user-plus"></i>
                    Add First Member
                </button>
            </div>
        @else
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Participant Details</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Club</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($registrations as $index => $reg)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">
                                            {{ $reg->peserta->nama_penuh ?? '-' }}
                                        </span>
                                        <span class="text-xs text-gray-500 font-mono mt-1">
                                            <i class="fa-solid fa-id-card mr-1"></i>
                                            {{ $reg->peserta->ic ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                        <i class="fa-solid fa-building text-xs"></i>
                                        {{ $reg->peserta->kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-700 rounded-full text-sm font-bold">
                                        <i class="fa-solid fa-tag text-xs"></i>
                                        {{ $reg->category_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <button wire:click="deleteParticipant({{ $reg->id }})"
                                        class="inline-flex items-center justify-center w-10 h-10 text-red-500 hover:text-white hover:bg-red-500 rounded-xl transition-all transform hover:scale-110"
                                        title="Remove participant">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-4">
                @foreach($registrations as $index => $reg)
                    <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-2xl p-5 hover:border-purple-300 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $reg->peserta->nama_penuh ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 font-mono">
                                        <i class="fa-solid fa-id-card mr-1"></i>
                                        {{ $reg->peserta->ic ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <button wire:click="deleteParticipant({{ $reg->id }})"
                                class="w-10 h-10 text-red-500 hover:bg-red-50 rounded-xl transition-all flex items-center justify-center">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-50 rounded-xl p-3 border border-blue-100">
                                <p class="text-xs text-blue-600 font-semibold uppercase mb-1">Club</p>
                                <p class="text-sm font-bold text-blue-700">{{ $reg->peserta->kelas ?? '-' }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-3 border border-purple-100">
                                <p class="text-xs text-purple-600 font-semibold uppercase mb-1">Category</p>
                                <p class="text-sm font-bold text-purple-700">{{ $reg->category_name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer Actions --}}
            <div class="mt-8 pt-6 border-t-2 border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Grand Total</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                            RM {{ number_format($totalAmount, 2) }}
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button wire:click="payLater"
                            class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl border-2 border-gray-300 bg-white text-gray-700 font-bold hover:bg-gray-50 hover:border-gray-400 transition-all">
                            <i class="fa-solid fa-clock"></i>
                            Pay Later
                        </button>

                        <button wire:click="payNow"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                            <i class="fa-solid fa-credit-card"></i>
                            Pay Now
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Info Card --}}
<div class="mt-6 bg-gradient-to-r from-blue-100 to-purple-100 border-2 border-blue-200 rounded-2xl p-6">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-info text-white"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 mb-2">Payment Information</h4>
            <ul class="text-gray-700 text-sm space-y-1">
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                    <span>You can pay now or choose to pay later</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                    <span>All payments are securely processed</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                    <span>You'll receive confirmation once payment is complete</span>
                </li>
            </ul>
        </div>
    </div>
</div>