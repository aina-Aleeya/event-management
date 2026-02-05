<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased">

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden">

        <!-- Base gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-100 via-pink-100 to-red-100"></div>

        <!-- Floating gradient blobs -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-300/40 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -left-24 w-96 h-96 bg-pink-300/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 right-1/3 w-80 h-80 bg-red-300/30 rounded-full blur-3xl"></div>

        <!-- Auth card -->
        <div class="relative z-10 w-full max-w-sm rounded-3xl
           bg-white/80
           backdrop-blur-xl backdrop-saturate-150
           border border-white/60
           shadow-[0_20px_50px_rgba(0,0,0,0.12)]
           p-6 md:p-8">
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>


    </div>

    @fluxScripts
</body>

</html>