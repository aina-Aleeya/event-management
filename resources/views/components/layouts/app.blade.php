<x-layouts.app.header :title="$title ?? null">
    <flux:main class="!max-w-full !px-0 !mx-0">
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>

<!-- Font Awesome 6 Free CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

