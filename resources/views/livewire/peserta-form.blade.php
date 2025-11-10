<div class="max-w-xl mx-auto p-6 bg-white shadow-lg rounded-2xl border border-gray-200">
    <h2 class="text-2xl font-bold mb-5 text-gray-800 text-center">Borang Pendaftaran</h2>

    @if($event)
        <p class="text-lg text-center text-gray-600 mb-5">
            {{ $event->title }}
        </p>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-success', (eventId) => {
                alert('Pendaftaran berjaya!');
                window.location.href = `/events/${eventId}/participants`; 
            });
        });
    </script>

    <form wire:submit.prevent="save" class="space-y-4 text-gray-800">
    @if(!Auth::check())
    <div class="border rounded p-4 mb-4 bg-gray-50">
        <h3 class="font-semibold mb-2">Maklumat Pendaftar (Guest)</h3>
        <div class="mb-2">
            <label>Nama Pendaftar</label>
            <input type="text" wire:model="pendaftar_nama" class="w-full border p-2 rounded">
        </div>
        <div>
            <label>Email Pendaftar</label>
            <input type="email" wire:model="pendaftar_email" class="w-full border p-2 rounded">
        </div>
    </div>
    @endif
    @foreach ($pesertas as $index => $peserta)
        <div class="border p-4 rounded-lg mb-3">
            <h3 class="font-semibold text-lg mb-2">Peserta {{ $index + 1 }}</h3>

            <!-- Nama Penuh -->
            <div>
                <label class="block text-gray-700 font-medium">Nama Penuh</label>
                <input type="text" wire:model.live.debounce="pesertas.{{ $index }}.nama_penuh" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">

                @if(!empty($suggestions[$index]))
                    <ul class="border border-gray-200 mt-1 bg-white rounded-md shadow-sm">
                        @foreach($suggestions[$index] as $s)
                            <li wire:click="fillForm({{ $s['id'] }}, {{ $index }})" class="px-3 py-2 hover:bg-blue-50 cursor-pointer transition-colors">
                                {{ $s['nama_penuh'] ?? '-' }} ({{ $s['kelas'] ?? '-' }})
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama Panggilan -->
                <div>
                    <label class="block text-gray-700 font-medium">Nama Panggilan</label>
                    <input type="text" wire:model="pesertas.{{ $index }}.nama_panggilan" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                </div>

                <!-- Kelab -->
                <div>
                    <label class="block text-gray-700 font-medium">Kelab</label>
                    <input type="text" wire:model="pesertas.{{ $index }}.kelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                </div>

                <!-- IC -->
                <div>
                    <label class="block text-gray-700 font-medium">No. Kad Pengenalan</label>
                    <input type="text" wire:model.live="pesertas.{{ $index }}.ic" maxlength="12" placeholder="tanpa '-'" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                </div>

                <!-- Tarikh lahir -->
                <div>
                    <label class="block text-gray-700 font-medium">Tarikh Lahir</label>
                    <input type="date" wire:model="pesertas.{{ $index }}.tarikh_lahir" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                </div>

                <!-- Jantina -->
                <div>
                    <label class="block text-gray-700 font-medium">Jantina</label>
                    <input type="text" wire:model="pesertas.{{ $index }}.jantina" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                </div>

                <!-- email -->
                <div>
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" wire:model="pesertas.{{ $index }}.email" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                </div>

                <!-- Gambar -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Gambar</label>

                    <!-- Hidden Input -->
                    <input  type="file" wire:model="pesertas.{{ $index }}.gambar" id="gambarInput{{ $index }}" class="hidden"  accept="image/*">

                    <!-- Upload Button -->
                    <div class="flex items-center space-x-3">
                        <label for="gambarInput{{ $index }}" class="inline-flex items-center px-2 py-1 bg-white-100 hover:bg-gray-200 text-black rounded-lg shadow-md cursor-pointer transition duration-150 whitespace-nowrap">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v9m0 0l-3-3m3 3l3-3M12 3v9" />
                            <i class="fi fi-rr-folder-upload text-black text-lg mr-2"></i>
                        Upload Picture
                        </label>

                        <!-- File name + remove icon -->
                        @if (!empty($pesertas[$index]['gambar']))
                                <div class="flex items-center border border-gray-200 bg-white-50 px-2 py-1 rounded-lg text-sm text-gray-700  w-56 justify-between">
                                    <span class="truncate">
                                        {{ $pesertas[$index]['gambar']->getClientOriginalName() }}
                                    </span>
                                    <button type="button" wire:click="$set('pesertas.{{ $index }}.gambar', null)" class="text-red-500 hover:text-red-700 transition" title="Remove file">
                                        ✕
                                    </button>
                                </div>
                        @endif
                    </div>
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-gray-700 font-medium">Kategori</label>
                    <div class="flex items-center space-x-6 mt-1">
                        <label class="flex items-center space-x-2">
                            <input 
                                type="radio" 
                                wire:model="pesertas.{{ $index }}.category" 
                                value="Individu" 
                                class="text-blue-500 focus:ring-blue-400"
                            >
                            <span>Individu</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input 
                                type="radio" 
                                wire:model="pesertas.{{ $index }}.category" 
                                value="Berkumpulan" 
                                class="text-blue-500 focus:ring-blue-400"
                            >
                            <span>Berkumpulan</span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="text-right">
                <button type="button" wire:click="removePeserta({{ $index }})" class="text-red-500 mt-2">Remove</button>
            </div>
        </div>
    @endforeach

    <div class="text-right">
        <button type="button" wire:click="addPeserta" class="bg-blue-500 text-white py-2 px-4 rounded-lg">
        + Tambah Peserta
        </button>
        @if (session()->has('maxPeserta'))
            <p class="text-red-500 text-sm mt-2">
                {{ session('maxPeserta') }}
            </p>
        @endif
    </div>

        <div class="flex justify-between gap-3 mt-4">
            <!-- Batal / Kembali Button -->
            <a 
                href="{{ url('/events/'.$idIklan) }}" 
                class="w-1/2 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg text-lg font-semibold shadow-md transition duration-150 flex items-center justify-center">
                ← Batal
            </a>

            <!-- Daftar Button -->
            <button 
                type="submit" 
                class="w-1/2 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-lg font-semibold shadow-md transition duration-150">
                Daftar
            </button>
        </div>
    </form>
</div>