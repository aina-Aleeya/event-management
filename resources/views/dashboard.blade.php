<x-layouts.app :title="__('Dashboard')">

    <!-- Hero Section with Gradient -->
    <section
        class="relative grid grid-cols-1 md:grid-cols-2 items-center justify-between min-h-[80vh] overflow-hidden px-6 md:px-16 lg:px-24 bg-gradient-to-br from-purple-50 via-pink-50 to-red-50">

        <!-- Decorative Elements -->
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-purple-300/30 to-pink-300/30 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-red-300/30 to-orange-300/30 rounded-full blur-3xl">
        </div>

        <div class="col-span-2 pt-6">
            @php
                $userId = auth()->id();
                $registrations = \App\Models\Penyertaan::with(['event', 'peserta', 'categorizable'])
                    ->where('pendaftar_id', $userId)
                    ->orderBy('id')
                    ->get();  
            @endphp

            @if($registrations->contains('status_bayaran', 'pending'))
                <div class="mt-8 bg-gradient-to-r from-yellow-100 to-orange-100 border-2 border-yellow-300 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">Pending Payment</h4>
                            <p class="text-gray-700 text-sm">
                                You have <span
                                    class="font-bold">{{ $registrations->where('status_bayaran', 'pending')->count() }}</span>
                                participant(s) with pending payment.
                                Please complete the payment to secure your registration.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Text content -->
        <div class="relative z-10 max-w-3xl space-y-6  py-6">

            <div
                class="inline-block px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-sm font-semibold rounded-full shadow-lg">
                🎉 Welcome to GreatEvent
            </div>

            <h1
                class="text-5xl md:text-6xl font-extrabold leading-tight tracking-tight bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 bg-clip-text text-transparent">
                Discover Amazing Events
            </h1>

            <p class="text-lg md:text-xl text-gray-700 leading-relaxed">
                Connect with people through shared experiences. Whether you're joining an exciting event or planning
                your own, our platform helps you celebrate with people who share your passions.
            </p>

            <div class="flex flex-wrap gap-4 pt-4">
                <a href="{{ route('events.page') }}"
                    class="px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    Explore Events →
                </a>
                <a href="#features"
                    class="px-8 py-4 bg-white text-gray-800 font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200 border border-gray-200">
                    Learn More
                </a>
            </div>
        </div>

        <!-- Visual Element -->
        <div class="relative z-10  flex items-center justify-center py-12">
            <div class="relative w-full max-w-lg">
                <!-- Floating Cards Animation -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div
                        class="absolute w-72 h-96 bg-gradient-to-br from-purple-400 to-pink-400 rounded-3xl shadow-2xl transform rotate-6 opacity-80">
                    </div>
                    <div
                        class="absolute w-72 h-96 bg-gradient-to-br from-pink-400 to-red-400 rounded-3xl shadow-2xl transform -rotate-6 opacity-80">
                    </div>
                    <div
                        class="relative w-80 h-[26rem] bg-white rounded-3xl shadow-2xl p-6 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="h-40 bg-gradient-to-br from-purple-200 to-pink-200 rounded-2xl"></div>
                            <div class="h-4 bg-gray-200 rounded-full w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded-full w-1/2"></div>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-1 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Animated Ticker -->
    <div
        class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white overflow-hidden whitespace-nowrap relative shadow-lg">
        <div class="py-4 flex animate-[marquee_40s_linear_infinite]">
            @php
                $tickerItems = ['🎪 Find your next adventure', '✨ Experience something new', '🎉 Register Now', '🎭 Join the community'];
                $items = array_merge(...array_fill(0, 15, $tickerItems));
            @endphp
            @foreach($items as $text)
                <div class="flex items-center space-x-8 mx-8">
                    <span class="text-sm font-bold uppercase tracking-widest">{{ $text }}</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-20 px-6 md:px-16 lg:px-24 bg-gradient-to-b from-white to-purple-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2
                    class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">
                    Why Choose GreatEvent?
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Everything you need to discover, join, and manage events in one place
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="group p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-purple-100 hover:border-purple-300">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Easy Discovery</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Find events that match your interests with our intuitive search and filtering system
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="group p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-pink-100 hover:border-pink-300">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-pink-500 to-red-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Connect & Network</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Meet like-minded people and build lasting connections through shared experiences
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="group p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-red-100 hover:border-red-300">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Simple Registration</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Quick and hassle-free event registration with instant confirmation
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-20 px-6 md:px-16 lg:px-24 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2
                        class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                        Upcoming Events
                    </h2>
                    <p class="text-gray-600 text-lg">
                        Check out the latest events happening soon
                    </p>
                </div>
                <a href="{{ route('events.page') }}"
                    class="hidden md:inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    View All Events
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <livewire:user.event-list :limit="4" />
        </div>
    </section>

    <!-- CTA Section -->
    <section
        class="py-20 px-6 md:px-16 lg:px-24 bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-4xl mx-auto text-center space-y-8">
            <h2 class="text-4xl md:text-5xl font-bold">
                Ready to Start Your Journey?
            </h2>
            <p class="text-xl text-white/90 max-w-2xl mx-auto">
                Join thousands of people discovering and attending amazing events every day
            </p>
            <div class="flex flex-wrap gap-4 justify-center pt-4">
                @guest
                    <a href="{{ route('register') }}"
                        class="px-10 py-4 bg-white text-purple-600 font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                        Get Started Free
                    </a>
                    <a href="{{ route('events.page') }}"
                        class="px-10 py-4 bg-white/10 backdrop-blur-sm text-white font-bold rounded-xl border-2 border-white hover:bg-white/20 transform hover:-translate-y-1 transition-all">
                        Browse Events
                    </a>
                @else
                    <a href="{{ route('events.page') }}"
                        class="px-10 py-4 bg-white text-purple-600 font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                        Explore More Events
                    </a>
                @endguest
            </div>
        </div>
    </section>

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