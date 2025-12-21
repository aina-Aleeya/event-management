<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    @include('partials.head')
    @livewireStyles

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="min-h-screen bg-white text-black">

<div x-data="{userMenuOpen: false }" class="relative min-h-screen">

    {{-- Header --}}
    <x-layouts.app.header />

    {{-- Page Content --}}
    <main class="pt-8.5">
        <flux:main class="!max-w-full !px-0 !mx-0">
            {{ $slot }}
        </flux:main>
    </main>

</div>

@livewireScripts
@fluxScripts
@stack('scripts')

<script>
    function sidebarComponent() {
        return {
            sidebarOpen: false,
            init() {
                window.addEventListener('livewire:navigating', () => {
                    this.sidebarOpen = false;
                });
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>

</body>
</html>
