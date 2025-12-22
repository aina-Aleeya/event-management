<x-layouts.app.admin>
    <x-slot name="breadcrumbs">
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.event.dashboard', $event->id) }}" class="hover:text-purple-600 transition">
            {{ $event->title }}
            </a>
        </li>
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Grouping Category</span>
        </li>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-6">
        <!-- Page Header -->
        <div class="mb-6 mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }} - Group Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Organize and manage participant groups by category</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Participants -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">Total Participants</p>
                        <p class="text-3xl font-bold text-blue-900">{{ $event->pesertas->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 mb-1">Categories</p>
                        <p class="text-3xl font-bold text-purple-900">{{ $categories->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Groups -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">Total Groups</p>
                        <p class="text-3xl font-bold text-green-900">{{ $event->groups->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">

            <!-- Search and Filter Bar -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search Box -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="searchInput" 
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" 
                                placeholder="Search categories..." />
                        </div>
                    </div>

                    <!-- Sort Buttons -->
                    <div class="flex items-center gap-2">
                        <button data-sort="asc" class="sort-btn px-4 py-2 text-sm font-medium rounded-lg border transition-all bg-blue-600 text-white border-blue-600">
                            A-Z
                        </button>
                        <button data-sort="desc" class="sort-btn px-4 py-2 text-sm font-medium rounded-lg border transition-all bg-white text-gray-700 border-gray-300 hover:bg-gray-50">
                            Z-A
                        </button>
                    </div>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="p-6">
                <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($categories as $category)
                        @php
                            $categoryCount = $event->pesertas->where('category', $category)->count();
                            $groupsCount = $event->groups->filter(function($group) use ($category) {
                                return str_starts_with($group->name, $category);
                            })->count();
                        @endphp

                        <a href="{{ route('admin.groups', ['event' => $event->id, 'category' => $category]) }}"
                           data-category="{{ strtolower($category) }}"
                           class="category-card group block bg-white p-5 border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:shadow-lg transition-all">
                            
                            <!-- Category Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                            {{ $category }}
                                        </h3>
                                        <p class="text-xs text-gray-500">Category</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>

                            <!-- Stats -->
                            <div class="space-y-2">
                                <!-- Participants -->
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="font-medium">Participants</span>
                                    </div>
                                    <span class="text-lg font-bold text-green-700">{{ $categoryCount }}</span>
                                </div>

                                <!-- Groups -->
                                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-100">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <span class="font-medium">Groups</span>
                                    </div>
                                    <span class="text-lg font-bold text-purple-700">{{ $groupsCount }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">No categories available</p>
                                <p class="text-gray-400 text-sm mt-1">Categories will appear once participants register</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer with Count -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Showing <span id="visibleCount" class="font-semibold text-gray-900">{{ $categories->count() }}</span> 
                        of <span class="font-semibold text-gray-900">{{ $categories->count() }}</span> categories
                    </p>
                    <div class="text-sm text-gray-500">
                        Last updated: {{ now()->format('d M Y, h:i A') }}
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- JavaScript for Search and Sort -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const sortButtons = document.querySelectorAll('.sort-btn');
            const categoriesGrid = document.getElementById('categoriesGrid');
            const categoryCards = Array.from(document.querySelectorAll('.category-card'));
            const visibleCountSpan = document.getElementById('visibleCount');

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let visibleCount = 0;

                categoryCards.forEach(card => {
                    const categoryName = card.dataset.category;
                    if (categoryName.includes(searchTerm)) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                visibleCountSpan.textContent = visibleCount;
            });

            // Sort functionality
            sortButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    sortButtons.forEach(btn => {
                        btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                    });
                    this.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    this.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

                    const sortOrder = this.dataset.sort;
                    sortCategories(sortOrder);
                });
            });

            function sortCategories(order) {
                const sorted = categoryCards.sort((a, b) => {
                    const nameA = a.dataset.category;
                    const nameB = b.dataset.category;
                    return order === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });

                sorted.forEach(card => categoriesGrid.appendChild(card));
            }
        });
    </script>
</x-layouts.app.admin>