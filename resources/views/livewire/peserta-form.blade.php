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
        @foreach ($pesertas as $index => $peserta)
            <div class="border p-4 rounded-lg mb-4">
                <h3 class="font-semibold text-lg mb-2">Peserta {{ $index + 1 }}</h3>

                <!-- Nama Penuh -->
                <div>
                    <label class="block text-gray-700 font-medium">Nama Penuh</label>
                    <input type="text" wire:model.live.debounce="pesertas.{{ $index }}.nama_penuh" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                            focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">

                    @if(!empty($suggestions[$index]))
                        <ul class="border border-gray-200 mt-1 bg-white rounded-md shadow-sm">
                            @foreach($suggestions[$index] as $s)
                                <li wire:click="fillForm({{ $s['id'] }}, {{ $index }})"
                                    class="px-3 py-2 hover:bg-blue-50 cursor-pointer transition-colors">
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
                        <input type="text" wire:model="pesertas.{{ $index }}.nama_panggilan" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                                focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                    </div>

                    <!-- Kelab -->
                    <div>
                        <label class="block text-gray-700 font-medium">Kelab</label>
                        <input type="text" wire:model="pesertas.{{ $index }}.kelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                                focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                    </div>

                    <!-- IC -->
                    <div>
                        <label class="block text-gray-700 font-medium">No. Kad Pengenalan</label>
                        <input type="text" wire:model.live="pesertas.{{ $index }}.ic" maxlength="12" placeholder="tanpa '-'"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                                focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                    </div>

                    <!-- Tarikh Lahir -->
                    <div>
                        <label class="block text-gray-700 font-medium">Tarikh Lahir</label>
                        <input type="date" wire:model="pesertas.{{ $index }}.tarikh_lahir" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 bg-white
                                focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                    </div>

                    <!-- Jantina -->
                    <div>
                        <label class="block text-gray-700 font-medium">Jantina</label>
                        <input type="text" wire:model="pesertas.{{ $index }}.jantina" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 bg-white
                                focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-medium">Email</label>
                        <input type="email" wire:model="pesertas.{{ $index }}.email" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                                focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
                    </div>

                    <!-- Gambar -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Gambar</label>

                        <!-- Hidden Input -->
                        <input type="file" wire:model="pesertas.{{ $index }}.gambar" id="gambarInput{{ $index }}"
                            class="hidden" accept="image/*">

                        <!-- Upload Button -->
                        <div class="flex items-center space-x-3">
                            <label for="gambarInput{{ $index }}"
                                class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200
                                    text-black rounded-lg shadow-md cursor-pointer transition duration-150 whitespace-nowrap">
                                <i class="fi fi-rr-folder-upload text-black text-lg mr-2"></i>
                                Upload Picture
                            </label>

                            @if (!empty($pesertas[$index]['gambar']))
                                <div class="flex items-center border border-gray-200 bg-white px-2 py-1
                                            rounded-lg text-sm text-gray-700 w-56 justify-between">
                                    <span class="truncate">
                                        {{ $pesertas[$index]['gambar']->getClientOriginalName() }}
                                    </span>
                                    <button type="button" wire:click="$set('pesertas.{{ $index }}.gambar', null)"
                                        class="text-red-500 hover:text-red-700 transition" title="Remove file">
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
                                <input type="radio" wire:model="pesertas.{{ $index }}.category" value="Individu"
                                    class="text-blue-500 focus:ring-blue-400">
                                <span>Individu</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" wire:model="pesertas.{{ $index }}.category" value="Berkumpulan"
                                    class="text-blue-500 focus:ring-blue-400">
                                <span>Berkumpulan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="button" wire:click="removePeserta({{ $index }})"
                        class="text-red-500 mt-3 hover:underline">Remove</button>
                </div>
            </div>
        @endforeach

        <!-- Tambah Peserta -->
        <div class="text-right">
            <button type="button" wire:click="addPeserta"
                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg shadow-md transition duration-150">
                + Tambah Peserta
            </button>
            @if (session()->has('maxPeserta'))
                <p class="text-red-500 text-sm mt-2">
                    {{ session('maxPeserta') }}
                </p>
            @endif
        </div>

        <!-- Butang bawah -->
        <div class="flex justify-between gap-3 mt-6">
            <a href="{{ url('/events/' . $idIklan) }}" class="w-1/2 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg
                text-lg font-semibold shadow-md transition duration-150 flex items-center justify-center">
                ← Batal
            </a>

            <button type="submit" class="w-1/2 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-lg
                font-semibold shadow-md transition duration-150">
                Daftar
            </button>
        </div>
    </form>
</div>