<x-layouts.app>

    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-lg mt-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Groups for {{ $event->title }}</h1>

            @foreach ($finalGroups as $category => $groups)
                <h2 class="text-xl font-semibold mt-6">{{ $category }}</h2>

                @foreach ($groups as $index => $group)
                    <div class="border p-4 my-2 rounded bg-gray-50">
                        <h3 class="font-bold">Group {{ $index + 1 }}</h3>
                        <ul class="list-disc ml-6">
                            @foreach ($group as $participant)
                                <li>{{ $participant->unique_id }} — {{ $participant->user->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</x-layouts.app>
