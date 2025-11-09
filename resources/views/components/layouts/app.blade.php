<x-layouts.app.header :title="$title ?? null">
    <flux:main class="!max-w-full !px-0 !mx-0">
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
