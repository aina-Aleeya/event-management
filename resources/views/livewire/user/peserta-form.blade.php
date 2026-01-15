<div class="min-h-screen bg-gradient-to-b from-purple-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header Section --}}
        <div class="text-center mb-8 mt-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl mb-4 shadow-lg">
                <i class="fa-solid fa-user-plus text-white text-2xl"></i>
            </div>
            <h2 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-3">
                Event Registration
            </h2>
            
            @if($event)
                <div class="inline-block bg-white px-6 py-3 rounded-xl shadow-md border border-purple-100">
                    <p class="text-lg font-semibold text-gray-800">
                        {{ $event->title }}
                    </p>
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('show-success', (eventId) => {
                    alert('Registration successful!');
                    window.location.href = `/payment/${eventId}`; 
                });
            });
        </script>

        {{-- Main Form Card --}}
        <div class="bg-white shadow-2xl rounded-3xl border border-gray-200 overflow-hidden">
            <form wire:submit.prevent="save" class="p-8 md:p-10 space-y-8">
                
                {{-- Guest Information Section (if not authenticated) --}}
                @if(!Auth::check())
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl p-6 space-y-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-user text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Registrant Information</h3>
                            <span class="px-3 py-1 bg-purple-200 text-purple-700 text-xs font-bold rounded-full">Guest</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Registrant Name *</label>
                                <input type="text" 
                                       wire:model="pendaftar_nama" 
                                       class="w-full border-2 border-purple-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                       placeholder="Enter your name">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Registrant Email *</label>
                                <input type="email" 
                                       wire:model="pendaftar_email" 
                                       class="w-full border-2 border-purple-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                       placeholder="your.email@example.com">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Participants Section --}}
                <div class="space-y-6">
                    @foreach ($pesertas as $index => $peserta)
                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-2xl p-6 md:p-8 space-y-6 hover:border-purple-300 transition-all">
                            
                            {{-- Participant Header --}}
                            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800">Participant {{ $index + 1 }}</h3>
                                </div>
                                @if(count($pesertas) > 1)
                                    <button type="button" 
                                            wire:click="removePeserta({{ $index }})" 
                                            class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all font-semibold">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span>Remove</span>
                                    </button>
                                @endif
                            </div>

                            {{-- Full Name with Autocomplete --}}
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Full Name *
                                </label>
                                <input type="text" 
                                       wire:model.live.debounce="pesertas.{{ $index }}.nama_penuh" 
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                       placeholder="Enter full name">

                                @if(!empty($suggestions[$index]))
                                    <ul class="mt-2 border-2 border-purple-200 bg-white rounded-xl shadow-lg overflow-hidden">
                                        @foreach($suggestions[$index] as $s)
                                            <li wire:click="fillForm({{ $s['id'] }}, {{ $index }})" 
                                                class="px-4 py-3 hover:bg-purple-50 cursor-pointer transition-colors border-b border-gray-100 last:border-0 flex items-center gap-3">
                                                <i class="fa-solid fa-user-circle text-purple-500"></i>
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ $s['nama_penuh'] ?? '-' }}</span>
                                                    <span class="text-sm text-gray-500 ml-2">({{ $s['kelas'] ?? '-' }})</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            
                            {{-- Form Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                {{-- Preferred Name --}}
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Preferred Name</label>
                                    <input type="text" 
                                           wire:model="pesertas.{{ $index }}.nama_panggilan" 
                                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                           placeholder="Nickname or preferred name">
                                </div>

                                {{-- Club --}}
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Club</label>
                                    <input type="text" 
                                           wire:model="pesertas.{{ $index }}.kelas" 
                                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                           placeholder="Club or class name">
                                </div>

                                {{-- MyKad Number --}}
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <i class="fa-solid fa-id-card text-purple-500 mr-1"></i>
                                        MyKad Number
                                    </label>
                                    <input type="text" 
                                           wire:model.live="pesertas.{{ $index }}.ic" 
                                           maxlength="12" 
                                           placeholder="without '-'" 
                                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Date of Birth --}}
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <i class="fa-solid fa-calendar text-purple-500 mr-1"></i>
                                        Date of Birth
                                    </label>
                                    <input type="date" 
                                           wire:model="pesertas.{{ $index }}.tarikh_lahir" 
                                           readonly 
                                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 bg-gray-100 cursor-not-allowed">
                                </div>

                                {{-- Gender --}}
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <i class="fa-solid fa-venus-mars text-purple-500 mr-1"></i>
                                        Gender
                                    </label>
                                    <input type="text" 
                                           wire:model="pesertas.{{ $index }}.jantina" 
                                           readonly 
                                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 bg-gray-100 cursor-not-allowed">
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <i class="fa-solid fa-envelope text-purple-500 mr-1"></i>
                                        Email
                                    </label>
                                    <input type="email" 
                                           wire:model="pesertas.{{ $index }}.email" 
                                           class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                           placeholder="email@example.com">
                                </div>

                                {{-- Picture Upload --}}
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <i class="fa-solid fa-image text-purple-500 mr-1"></i>
                                        Picture
                                    </label>

                                    <input type="file" 
                                           wire:model="pesertas.{{ $index }}.gambar" 
                                           id="gambarInput{{ $index }}" 
                                           class="hidden" 
                                           accept="image/*">

                                    <div class="flex items-center gap-3">
                                        <label for="gambarInput{{ $index }}" 
                                               class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl cursor-pointer transition-all shadow-md hover:shadow-lg font-semibold">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            Upload Picture
                                        </label>

                                        @if (!empty($pesertas[$index]['gambar']))
                                            <div class="flex items-center gap-2 border-2 border-purple-200 bg-purple-50 px-4 py-2 rounded-xl text-sm text-gray-700 flex-1 max-w-xs">
                                                <i class="fa-solid fa-file-image text-purple-500"></i>
                                                <span class="truncate flex-1">
                                                    {{ $pesertas[$index]['gambar']->getClientOriginalName() }}
                                                </span>
                                                <button type="button" 
                                                        wire:click="$set('pesertas.{{ $index }}.gambar', null)" 
                                                        class="text-red-500 hover:text-red-700 transition font-bold" 
                                                        title="Remove file">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Categories Section --}}
                            <div class="pt-4 border-t border-gray-200">
                                <label class="block text-gray-700 font-semibold mb-3">
                                    <i class="fa-solid fa-tags text-purple-500 mr-1"></i>
                                    Categories *
                                </label>
                                <div class="border-2 border-purple-200 rounded-xl p-5 bg-gradient-to-br from-purple-50 to-pink-50">
                                    @if(count($eventCategories) > 0)
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach($eventCategories as $category)
                                                <label class="flex items-center gap-3 bg-white hover:bg-purple-50 p-4 rounded-xl cursor-pointer transition-all border-2 border-transparent hover:border-purple-300 group">
                                                    <input 
                                                        type="checkbox" 
                                                        wire:model="pesertas.{{ $index }}.selected_categories" 
                                                        value="{{ $category['id'] }}"
                                                        class="w-5 h-5 text-purple-500 focus:ring-purple-400 rounded border-2 border-gray-300"
                                                    >
                                                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">
                                                        {{ $category['name'] }}
                                            
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <i class="fa-solid fa-inbox text-gray-400 text-4xl mb-3"></i>
                                            <p class="text-gray-500">No categories available for this event.</p>
                                        </div>
                                    @endif
                                </div>
                                @error('pesertas.'.$index.'.selected_categories')
                                    <p class="mt-2 text-red-600 text-sm flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Add Participant Button --}}
                <div class="flex justify-center">
                    <button type="button" 
                            wire:click="addPeserta" 
                            class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-user-plus text-xl"></i>
                        <span>Add Another Participant</span>
                    </button>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ url('/events/'.$idIklan) }}" 
                       class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 py-4 rounded-xl text-lg font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Cancel</span>
                    </a>

                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-4 rounded-xl text-lg font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>Complete Registration</span>
                    </button>
                </div>

            </form>
        </div>

        {{-- Help Text --}}
        <div class="mt-8 text-center">
            <p class="text-gray-600 text-sm">
                <i class="fa-solid fa-circle-info text-purple-500 mr-1"></i>
                Fields marked with * are required. Please fill in all participant information accurately.
            </p>
        </div>

    </div>
</div>