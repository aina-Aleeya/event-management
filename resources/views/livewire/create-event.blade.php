<div>
<x-layouts.app>
<div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-2xl mt-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Create New Event</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-5" enctype="multipart/form-data">
        <div>
            <label class="block font-medium text-gray-700">Event Title</label>
            <input type="text" wire:model="title" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium text-gray-700">Poster</label>
            <input type="file" wire:model="poster" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
            @if ($poster)
                <img src="{{ $poster->temporaryUrl() }}" class="mt-3 w-48 rounded-lg shadow">
            @endif
            @error('poster') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Start Date</label>
                <input type="date" wire:model="start_date" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-700">End Date</label>
                <input type="date" wire:model="end_date" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block font-medium text-gray-700">Location</label>
            <input type="text" wire:model="location" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
            @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Ads Start Date</label>
                <input type="date" wire:model="ads_start" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
                @error('ads_start') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-700">Ads End Date</label>
                <input type="date" wire:model="ads_end" class="w-full border-gray-300 rounded-lg shadow-sm mt-1">
                @error('ads_end') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Save Event
        </button>
    </form>
</div>
</x-layouts.app>
</div>