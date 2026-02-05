<div class="min-h-screen bg-gradient-to-b from-purple-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="text-center mb-10 mt-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl mb-4 shadow-lg">
                <i class="fa-solid fa-users text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                Registered Participants
            </h1>
            <p class="text-gray-600 text-lg">
                View all participants for this event
            </p>
        </div>

        @if($registrations->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white shadow-2xl rounded-3xl border border-gray-200 overflow-hidden">
                <div class="text-center py-20 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                        <i class="fa-solid fa-user-slash text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">No Participants Yet</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        There are no registered participants for this event yet.
                    </p>
                    <a href="{{ route('history') }}" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to History
                    </a>
                </div>
            </div>
        @else
            {{-- Event Title Card --}}
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl p-6 mb-8 shadow-xl text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-white flex items-center justify-center gap-3">
                    <i class="fa-solid fa-calendar-check"></i>
                    {{ $registrations->first()->event->title ?? 'Event Tidak Dikenali' }}
                </h2>
                <p class="text-white/90 mt-2">
                    <i class="fa-solid fa-user-group mr-2"></i>
                    Total Participants: <span class="font-bold">{{ $registrations->count() }}</span>
                </p>
            </div>

            {{-- Payment Info Card --}}
            @if($registrations->contains('status_bayaran', 'pending'))
                <div class="mt-8 bg-gradient-to-r from-yellow-100 to-orange-100 border-2 border-yellow-300 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">Pending Payment</h4>
                            <p class="text-gray-700 text-sm">
                                You have <span class="font-bold">{{ $registrations->where('status_bayaran', 'pending')->count() }}</span> participant(s) with pending payment. 
                                Please complete the payment to secure your registration.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-8 bg-gradient-to-r from-green-100 to-emerald-100 border-2 border-green-300 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-circle-check text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">All Payments Complete!</h4>
                            <p class="text-gray-700 text-sm">
                                All participants have completed their payment. Your registration is confirmed!
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Participants Table Card --}}
            <div class="bg-white shadow-2xl rounded-3xl border border-gray-200 overflow-hidden mb-8 mt-8">
                
                {{-- Desktop Table View --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-800 to-gray-700 text-white">
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider w-20">
                                    No.
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-user mr-2"></i>
                                    Full Name
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-id-card mr-2"></i>
                                    MyKad
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-building mr-2"></i>
                                    Club
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-tag mr-2"></i>
                                    Category
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-credit-card mr-2"></i>
                                    Payment
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-clock mr-2"></i>
                                    Registration Date
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">
                                    
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($registrations as $index => $reg)
                                <tr class="hover:bg-purple-50 transition-colors duration-200">
                                    <td class="px-6 py-5 text-center">
                                        <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl text-white font-bold shadow-md">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="font-semibold text-gray-900">
                                            {{ $reg->peserta->nama_penuh ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="font-mono text-gray-700">
                                            {{ $reg->peserta->ic ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                            {{ $reg->peserta->kelas ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                                            <i class="fa-solid fa-tag text-xs"></i>
                                            {{ $reg->category_name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if(strtolower($reg->status_bayaran ?? '') === 'pending')
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full font-bold">
                                                <i class="fa-solid fa-clock"></i>
                                                Pending
                                            </span>
                                        @elseif(strtolower($reg->status_bayaran ?? '') === 'complete')
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-full font-bold">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Paid
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-bold">
                                                {{ ucfirst($reg->status_bayaran ?? '-') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="font-mono text-gray-700">
                                            {{ $reg->tarikh_daftar->format('d/m/Y') ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold">
                                            <a href="{{ route('participant.update', ['eventId' => $reg->event_id,'peserta_id' => $reg->peserta_id]) }}">Edit</a>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile/Tablet Card View --}}
                <div class="lg:hidden divide-y divide-gray-200">
                    @foreach($registrations as $index => $reg)
                        <div class="p-6 hover:bg-purple-50 transition-colors">
                            
                            {{-- Participant Header --}}
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md flex-shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 text-lg mb-1">
                                        {{ $reg->peserta->nama_penuh ?? '-' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 font-mono">
                                        <i class="fa-solid fa-id-card mr-1"></i>
                                        {{ $reg->peserta->ic ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Info Grid --}}
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                {{-- Club --}}
                                <div class="bg-blue-50 rounded-xl p-3 border-2 border-blue-100">
                                    <p class="text-xs text-blue-600 font-semibold uppercase mb-1">Club</p>
                                    <p class="text-sm font-bold text-blue-700">
                                        {{ $reg->peserta->kelas ?? '-' }}
                                    </p>
                                </div>

                                {{-- Category --}}
                                <div class="bg-purple-50 rounded-xl p-3 border-2 border-purple-100">
                                    <p class="text-xs text-purple-600 font-semibold uppercase mb-1">Category</p>
                                    <p class="text-sm font-bold text-purple-700 flex items-center gap-1">
                                        <i class="fa-solid fa-tag text-xs"></i>
                                        {{ $reg->category_name ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Payment Status --}}
                            <div class="flex justify-end">
                                @if(strtolower($reg->status_bayaran ?? '') === 'pending')
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-xl font-bold border-2 border-yellow-200">
                                        <i class="fa-solid fa-clock"></i>
                                        Pending Payment
                                    </span>
                                @elseif(strtolower($reg->status_bayaran ?? '') === 'paid')
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-xl font-bold border-2 border-green-200">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl font-bold border-2 border-gray-200">
                                        {{ ucfirst($reg->status_bayaran ?? '-') }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                <a href="{{ route('history') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-bold shadow-md hover:shadow-lg transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to History
                </a>

                @if($registrations->contains('status_bayaran', 'pending'))
                    <a href="{{ route('payment.form', ['event_id' => $eventId]) }}"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-credit-card"></i>
                        Complete Payment
                        <span class="ml-2 px-2.5 py-0.5 bg-white/30 rounded-full text-xs">
                            {{ $registrations->where('status_bayaran', 'pending')->count() }}
                        </span>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
