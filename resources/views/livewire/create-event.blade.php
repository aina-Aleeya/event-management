<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8 mt-10 space-y-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Create New Event</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- BASIC INFO -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div>
                    <label class="font-medium">Event Title</label>
                    <input type="text" wire:model="title" class="w-full border rounded-lg p-2">
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Event Type -->
                <div>
                    <label class="font-medium">Event Type</label>
                    <select wire:model="event_type" class="w-full border rounded-lg p-2">
                        <option value="" selected>-- Select Event Type --</option>
                        <option value="Sports">Sports</option>
                        <option value="Entertainments">Entertainments</option>
                        <option value="Seminars">Seminars</option>
                        <option value="Exhibitions">Exhibitions</option>
                        <option value="Business">Business</option>
                        <option value="Others">Others</option>
                    </select>
                    @error('event_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Venue -->
                <div>
                    <label class="font-medium">Venue</label>
                    <input type="text" wire:model="venue" class="w-full border rounded-lg p-2">
                    @error('venue')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label class="font-medium">City</label>
                    <input type="text" wire:model="city" class="w-full border rounded-lg p-2">
                    @error('city')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="font-medium">Contact Email</label>
                    <input type="email" wire:model="contact_email" class="w-full border rounded-lg p-2">
                    @error('contact_email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="font-medium">Contact Phone</label>
                    <input type="text" wire:model="contact_phone" class="w-full border rounded-lg p-2">
                    @error('contact_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- DESCRIPTION (Rich Text Editor) -->
            <div class="mt-4" wire:ignore>
                <label class="font-medium">Description</label>
                <textarea id="description" rows="5" class="w-full border rounded-lg p-2"
                    placeholder="Type your event description...">
                    {{ $description }}
                </textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- POSTER -->
            <div class="mt-4">
                <label class="font-medium mb-1 block">Poster</label>

                <!-- Styled Choose Files Button -->
                <label
                    class="inline-block px-4 py-2 bg-blue-500 text-white font-medium rounded-lg cursor-pointer hover:bg-blue-600 transition">
                    Choose Files
                    <input type="file" wire:model="posters" multiple class="hidden">
                </label>

                <!-- Preview Images -->
                @if ($posters && count($posters) > 0)
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($posters as $index => $image)
                            <div class="relative w-32 h-32 rounded-lg overflow-hidden border shadow-sm">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">

                                <!-- Remove Button -->
                                <button type="button" wire:click.prevent="removePoster({{ $index }})"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @error('posters')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- EVENT DATES -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Event Dates</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Start Date</label>
                    <input type="date" wire:model="start_date" class="w-full border rounded-lg p-2">
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="font-medium">End Date</label>
                    <input type="date" wire:model="end_date" class="w-full border rounded-lg p-2">
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="font-medium">Start Time</label>
                    <input type="time" wire:model="start_time" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="font-medium">End Time</label>
                    <input type="time" wire:model="end_time" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="font-medium">Registration Deadline</label>
                    <input type="date" wire:model="registration_deadline" class="w-full border rounded-lg p-2">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Categories</h3>
            <div class="flex flex-wrap gap-4">
                @foreach (['Adult Male', 'Adult Female', 'Kids', 'Senior', 'Open'] as $cat)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="categories" value="{{ $cat }}">
                        <span>{{ $cat }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- OPTIONAL -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Optional Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Entry Fee (RM)</label>
                    <input type="number" wire:model="entry_fee" step="0.01" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="font-medium">Max Participants</label>
                    <input type="number" wire:model="max_participants" class="w-full border rounded-lg p-2">
                </div>
            </div>
        </div>

        <!-- ADS -->
        <div>
            <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Advertisement</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Ads Start Date</label>
                    <input type="date" wire:model="ads_start_date" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="font-medium">Ads End Date</label>
                    <input type="date" wire:model="ads_end_date" class="w-full border rounded-lg p-2">
                </div>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Save Event
            </button>
        </div>
    </form>

    <!-- TINYMCE SCRIPT -->
    <script src="https://cdn.tiny.cloud/1/1qbsfz0jz8ba91vynqnrlwj6cmuforoc5nroznedh5wbpozf/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        function initTinyMCE() {
            tinymce.remove(); // clear existing editors first
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
</div>
