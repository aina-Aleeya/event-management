<x-layouts.app :title="__('Dashboard')">

    <!-- Hero Section -->
<!-- Hero Section -->
<section
    class="relative flex flex-col md:flex-row items-center justify-between h-[75vh] overflow-hidden px-10 text-gray-800 bg-white">

    <!-- Overlay for subtle contrast (optional) -->
    <div class="absolute inset-0 bg-black/10"></div>

    <!-- Text content -->
    <div class="relative z-10 max-w-3xl space-y-5 md:w-1/2">
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-md text-gray-900">
            Discover, Join & Create Amazing Events
        </h1>

        <p class="text-base md:text-lg text-gray-700 leading-relaxed tracking-normal text-justify">
            Connect with people through shared experiences. Whether you’re joining an exciting event or planning
            your own, our platform helps you connect, create, and celebrate with people who share your passions.
        </p>

        @auth
            <!-- If logged in -->
            <a href="{{ route('create-event') }}"
               class="inline-block bg-red-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg 
                      hover:bg-red-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-out z-20">
                + Create Event
            </a>
        @else
            <!-- If not logged in -->
            <button x-data @click="
                    if (confirm('You need to login first to create an event')) {
                        window.location = '{{ route('login') }}';
                    }
                "
                class="inline-block bg-red-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg 
                       hover:bg-red-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-out z-20">
                + Create Event
            </button>
        @endauth
    </div>
</section>

    <!-- Ticker Section -->
       <div class="bg-gradient-to-r from-[#ff3c3c] via-[#ff8a00] to-[#ffe500] text-white overflow-hidden whitespace-nowrap relative shadow-inner">
        <div class="py-4 flex animate-[marquee_40s_linear_infinite]">
            @php
                $tickerItems = ['Find your next adventure', 'Experience something new', 'Register Now'];
                $items = array_merge(...array_fill(0, 20, $tickerItems));
            @endphp
            @foreach($items as $text)
                <div class="flex items-center space-x-4 mx-4 opacity-90 hover:opacity-100 transition-opacity">
                    <span class="text-sm font-semibold uppercase tracking-widest">{{ $text }}</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 
                            1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 
                            2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 
                            1.688-1.54 1.118l-2.8-2.034a1 1 0 
                            00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 
                            1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 
                            1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Search and Event List -->

    <livewire:event-list :limit="4" />

</x-layouts.app>

<style>
@keyframes marquee {
    0% {
        transform: translateX(0%);
    }

    100% {
        transform: translateX(-50%);
    }
}
</style>
