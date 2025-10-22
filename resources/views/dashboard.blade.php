<x-layouts.app :title="__('Dashboard')">

<section class="relative flex flex-col md:flex-row items-center justify-between h-[70vh] overflow-hidden px-10 text-white bg-gradient-to-r from-purple-300 via-purple-200 to-sky-200">

    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Text content -->
    <div class="relative z-10 max-w-3xl space-y-4 md:w-1/2">
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">
            Discover, Join, and Create Amazing Events
        </h1>
        <p class="text-lg text-gray-100 leading-relaxed tracking-normal text-justify">
            Connect with people through shared experiences. Whether you’re looking for something fun to join or planning your own event,
            our community makes it simple to participate, create, and share the moment with others who share your passions.
        </p>

        <!-- Create Event Button -->
        <a href="{{ route('create-event') }}"
           class="inline-block bg-purple-300 text-gray-900 font-semibold px-6 py-3 rounded-lg shadow-md 
                  hover:bg-sky-300 transition duration-300 ease-in-out z-20">
            Create Event
        </a>
    </div>

<!-- Right Image -->
<div class="relative z-10 mt-10 md:mt-0 md:w-1/2 flex justify-end">
    <img src="{{ asset('img/image1.jpeg') }}" 
         alt="Event illustration"
         class="w-[350px] md:w-[500px] lg:w-[550px] object-contain drop-shadow-xl">
</div>

</section>

<!-- Event Box Section -->
<livewire:event-list />

</section>

</x-layouts.app>
