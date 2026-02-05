
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-6xl mx-auto px-6">
            {{-- Page Header --}}
            <div class="mb-8">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Create New Event
                </h2>
                <p class="text-gray-600 mt-2">Fill in the details below to create a new event</p>
            </div>

            @if (session()->has('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-6">
                {{-- BASIC INFO --}}
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Basic Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title</label>
                            <input type="text" wire:model="title" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            @error('title')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Event Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Type</label>
                            <select wire:model="event_type" 
                                    class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                <option value="" selected>-- Select Event Type --</option>
                                <option value="Sports">Sports</option>
                                <option value="Entertainments">Entertainments</option>
                                <option value="Seminars">Seminars</option>
                                <option value="Exhibitions">Exhibitions</option>
                                <option value="Business">Business</option>
                                <option value="Others">Others</option>
                            </select>
                            @error('event_type')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Venue -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Venue</label>
                            <input type="text" wire:model="venue" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            @error('venue')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                            <input type="text" wire:model="city" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            @error('city')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Email</label>
                            <input type="email" wire:model="contact_email" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            @error('contact_email')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" wire:model="contact_phone" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            @error('contact_phone')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mt-6" wire:ignore>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" rows="5" 
                                  class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                  placeholder="Type your event description...">{{ $description }}</textarea>
                        @error('description')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- POSTER -->
                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Event Poster</label>

                        <label class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold rounded-xl cursor-pointer hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Choose Files
                            <input type="file" wire:model="posters" multiple class="hidden">
                        </label>

                        @if ($posters && count($posters) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4">
                                @foreach ($posters as $index => $image)
                                    <div class="relative group rounded-xl overflow-hidden border-2 border-gray-200 shadow-md hover:shadow-xl transition-all">
                                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-40 object-cover">
                                        <button type="button" wire:click.prevent="removePoster({{ $index }})"
                                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @error('posters')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- EVENT DATES --}}
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Event Dates</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                            <input type="date" wire:model="start_date" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            @error('start_date')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                            <input type="date" wire:model="end_date" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            @error('end_date')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                            <input type="time" wire:model="start_time" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                            <input type="time" wire:model="end_time" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Deadline</label>
                            <input type="date" wire:model="registration_deadline" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                    </div>
                </div>

                {{-- CATEGORIES --}}
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-blue-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Categories</h3>
                    </div>

                    <!-- Default Categories -->
                    <div class="mb-6">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Available Categories:</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($allCategories as $category)
                                <label class="flex items-center p-3 hover:bg-blue-50 rounded-lg cursor-pointer border-2 border-gray-200 hover:border-blue-300 transition-all">
                                    <input type="checkbox" wire:model="selectedDefaultCategories" value="{{ $category->id }}"
                                           class="mr-3 w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-200">
                                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Selected Categories Display -->
                    @if(count($selectedDefaultCategories) > 0)
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-100 mb-6">
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                Selected Categories ({{ count($selectedDefaultCategories) }}):
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedDefaultCategories as $id)
                                    @php $cat = $allCategories->firstWhere('id', $id); @endphp
                                    @if($cat)
                                        <span class="px-4 py-2 bg-blue-600 text-white rounded-full text-sm font-medium shadow-md">
                                            {{ $cat->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Custom Categories -->
                    <div class="border-t pt-6">
                        <label class="text-sm font-semibold text-gray-700 mb-3 block">Custom Categories</label>
                        <div class="space-y-3">
                            @foreach($customCategoryList as $index => $value)
                                <div class="flex items-center gap-3">
                                    <input type="text" 
                                           class="flex-1 border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                           wire:model="customCategoryList.{{ $index }}"
                                           placeholder="Enter category name">
                                    <button class="px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all font-medium shadow-md"
                                            wire:click.prevent="removeCustomCategory({{ $index }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button class="mt-4 px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all font-semibold shadow-lg flex items-center gap-2"
                                wire:click.prevent="addCustomCategory">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Custom Category
                        </button>
                    </div>
                </div>

                {{-- OPTIONAL DETAILS --}}
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Optional Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Entry Fee (RM)</label>
                            <input type="number" wire:model="entry_fee" step="0.01" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Max Participants</label>
                            <input type="number" wire:model="max_participants" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                    </div>
                </div>

                {{-- ADVERTISEMENT --}}
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-blue-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Advertisement Period</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ads Start Date</label>
                            <input type="date" wire:model="ads_start_date" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ads End Date</label>
                            <input type="date" wire:model="ads_end_date" 
                                   class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold shadow-lg">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TINYMCE SCRIPT -->
    <script src="https://cdn.tiny.cloud/1/1qbsfz0jz8ba91vynqnrlwj6cmuforoc5nroznedh5wbpozf/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        function initTinyMCE() {
            tinymce.remove();
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: 'lists link image media table code',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link image media table | code',
                placeholder: "Type your event description...",
                setup: function(editor) {
                    editor.on('Change KeyUp', function() {
                        @this.set('description', editor.getContent());
                    });
                }
            });
        }

        document.addEventListener('livewire:load', function() {
            initTinyMCE();
        });

        document.addEventListener('livewire:navigated', function() {
            initTinyMCE();
        });
    </script>