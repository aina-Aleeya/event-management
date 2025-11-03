
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
                window.location.href = `/events/${eventId}`; 
            });
        });
    </script>

    <form wire:submit.prevent="save" class="space-y-4 text-gray-800">
        <!-- Nama Penuh -->
        <div>
            <label class="block text-gray-700 font-medium">Nama Penuh</label>
            <input type="text" wire:model.live.debounce="nama_penuh" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">

            @if($suggestions && $suggestions->isNotEmpty())
                <ul class="border border-gray-200 mt-1 bg-white rounded-md shadow-sm">
                    @foreach($suggestions as $s)
                        <li wire:click="fillForm({{ $s->id }})" class="px-3 py-2 hover:bg-blue-50 cursor-pointer transition-colors">
                            {{ $s->nama_penuh }} ({{ $s->kelas ?? '-' }})
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Nama Panggilan -->
        <div>
            <label class="block text-gray-700 font-medium">Nama Panggilan</label>
            <input type="text" wire:model="nama_panggilan" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
        </div>

        <!-- Kelab -->
        <div>
            <label class="block text-gray-700 font-medium">Kelab</label>
            <input type="text" wire:model="kelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
        </div>

        <!-- IC -->
        <div>
            <label class="block text-gray-700 font-medium">No. Kad Pengenalan (tanpa '-')</label>
            <input type="text" wire:model.live="ic" maxlength="12" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
        </div>

        <!-- Tarikh lahir -->
        <div>
            <label class="block text-gray-700 font-medium">Tarikh Lahir</label>
            <input type="date" wire:model="tarikh_lahir" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- Jantina -->
        <div>
            <label class="block text-gray-700 font-medium">Jantina</label>
            <input type="text" wire:model="jantina" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- email -->
        <div>
            <label class="block text-gray-700 font-medium">Email</label>
            <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white">
        </div>

         <!-- Gambar -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Gambar</label>

            <!-- Hidden Input -->
            <input  type="file" wire:model="gambar" id="gambarInput" class="hidden"  accept="image/*">

            <!-- Upload Button -->
            <label for="gambarInput" class="inline-flex items-center w-44 px-4 py-2 bg-white-100 hover:bg-gray-200 text-black rounded-lg shadow-md cursor-pointer transition duration-150">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v9m0 0l-3-3m3 3l3-3M12 3v9" />
                </svg> -->
                <i class="fi fi-rr-folder-upload text-black text-lg mr-2"></i>
                Upload Picture
            </label>

            <!-- File name + remove icon -->
            @if ($gambar)
                <div class="mt-3 flex items-center">
                    <div class="w-44 flex items-center justify-between border border-gray-200 bg-white-50 px-3 py-2 rounded-lg text-sm text-gray-700">
                        <span class="truncate">
                            {{ $gambar->getClientOriginalName() }}
                        </span>
                        <button type="button" wire:click="$set('gambar', null)" class="text-red-500 hover:text-red-700 transition" title="Remove file">
                            ✕
                        </button>
                    </div>
                </div>
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
