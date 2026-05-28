<x-landlord-layout>
    <x-slot:title>My Properties - LandForDays</x-slot:title>

    <div x-data="{ 
        addModalOpen: false, 
        editModalOpen: false, 
        editProp: { id: '', name: '', type: 'house', address: '', city: '', description: '' },
        search: '',
        page: 1,
        perPage: 10,
        totalMatching: 0,
        init() {
            this.updateRows();
            this.$watch('search', () => { this.page = 1; this.updateRows(); });
            this.$watch('page', () => { this.updateRows(); });
        },
        updateRows() {
            this.$nextTick(() => {
                const container = this.$refs.listContainer;
                if (!container) return;
                const items = container.querySelectorAll('[data-search]');
                const query = this.search.toLowerCase().trim();
                let matching = [];
                items.forEach(item => {
                    const text = item.getAttribute('data-search').toLowerCase();
                    if (text.includes(query)) {
                        matching.push(item);
                    } else {
                        item.style.display = 'none';
                    }
                });
                this.totalMatching = matching.length;
                const start = (this.page - 1) * this.perPage;
                const end = start + this.perPage;
                matching.forEach((item, idx) => {
                    if (idx >= start && idx < end) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        },
        get totalPages() {
            return Math.ceil(this.totalMatching / this.perPage) || 1;
        }
    }">
        <!-- Header Section -->
        <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 select-none">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">My Properties</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Create, view, edit and archive your active real estate holdings.
                </p>
            </div>
            <div>
                <button @click="addModalOpen = true" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all duration-200 cursor-pointer">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Add Property
                </button>
            </div>
        </div>

        @if ($properties->isEmpty())
            <!-- Empty State -->
            <div class="p-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm max-w-2xl mx-auto mt-10">
                <div class="p-4 bg-indigo-50 dark:bg-indigo-950/30 rounded-full w-20 h-20 flex items-center justify-center mx-auto text-indigo-600 dark:text-indigo-400 mb-6 shadow-sm">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white">No Properties Found</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Get started by adding your very first property to the LandForDays platform.
                </p>
                <div class="mt-8">
                    <button @click="addModalOpen = true" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md">
                        Add First Property
                    </button>
                </div>
            </div>
        @else
            <!-- Search Bar -->
            <div class="mb-8 relative max-w-md select-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Search properties by name, type, city, address..." 
                    class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm placeholder-slate-400 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm outline-none"
                />
            </div>

            <!-- Grid List -->
            <div x-ref="listContainer" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($properties as $property)
                    <div data-search="{{ $property->name }} {{ $property->type }} {{ $property->city }} {{ $property->address }}" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full group">
                        <!-- Card Header Accent -->
                        <div class="h-3 bg-gradient-to-r from-indigo-500 to-purple-500"></div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Title and badge -->
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $property->name }}</h3>
                                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-wider font-semibold">{{ $property->city }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900">
                                        {{ $property->type }}
                                    </span>
                                </div>

                                <!-- Details -->
                                <div class="mt-6 space-y-3.5">
                                    <div class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                                        <svg class="flex-shrink-0 mr-2.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $property->address }}</span>
                                    </div>

                                    @if ($property->description)
                                        <p class="text-xs text-slate-400 dark:text-slate-500 line-clamp-2 italic">
                                            "{{ $property->description }}"
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Occupancy Tracker -->
                            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">Units Tracker</span>
                                    <span class="text-xs font-medium text-slate-500">
                                        {{ $property->units_count - $property->vacant_units_count }} / {{ $property->units_count }} Occupied
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                    @php
                                        $occupancyRate = $property->units_count > 0 ? (($property->units_count - $property->vacant_units_count) / $property->units_count) * 100 : 0;
                                    @endphp
                                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $occupancyRate }}%"></div>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-slate-400">
                                    <span class="flex items-center">
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 mr-1.5 inline-block"></span>
                                        {{ $property->vacant_units_count }} Vacant
                                    </span>
                                    <span class="font-medium text-indigo-500 dark:text-indigo-400">
                                        {{ $property->units_count }} total units
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2.5 select-none">
                                <a href="{{ route('landlord.properties.show', $property->id) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-xl text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white transition-all duration-200">
                                    Units & Details &rarr;
                                </a>

                                <button @click="editProp = { id: '{{ $property->id }}', name: '{{ addslashes($property->name) }}', type: '{{ $property->type }}', address: '{{ addslashes($property->address) }}', city: '{{ addslashes($property->city) }}', description: '{{ addslashes($property->description) }}' }; editModalOpen = true;" class="p-2.5 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 bg-slate-50 dark:bg-slate-900 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Edit Property">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <form method="POST" action="{{ route('landlord.properties.destroy', $property->id) }}" onsubmit="return confirm('Are you sure you want to archive this property? All units and associations will be archived but not deleted.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-500 bg-slate-50 dark:bg-slate-900 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Archive Property">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty Search Results State -->
            <div x-show="totalMatching === 0" class="p-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm max-w-2xl mx-auto mt-6" style="display: none;" x-cloak>
                <div class="p-4 bg-slate-100 dark:bg-slate-800/50 rounded-full w-16 h-16 flex items-center justify-center mx-auto text-slate-400 dark:text-slate-500 mb-4 shadow-inner">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-slate-900 dark:text-white">No properties match search</h4>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Try checking your spelling or search terms.</p>
            </div>

            <!-- Pagination Footer -->
            <div x-show="totalPages > 1" class="mt-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center justify-between select-none" style="display: none;" x-cloak>
                <div class="text-xs text-slate-500">
                    Showing page <span class="font-bold text-slate-700 dark:text-slate-300" x-text="page"></span> of <span class="font-bold text-slate-700 dark:text-slate-300" x-text="totalPages"></span> (<span x-text="totalMatching"></span> matching properties)
                </div>
                <div class="flex items-center space-x-2">
                    <button 
                        type="button"
                        @click="if (page > 1) { page-- }" 
                        :disabled="page === 1"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all"
                    >
                        Previous
                    </button>
                    <button 
                        type="button"
                        @click="if (page < totalPages) { page++ }" 
                        :disabled="page === totalPages"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all"
                    >
                        Next
                    </button>
                </div>
            </div>
        @endif

        <!-- ADD PROPERTY MODAL -->
        <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>

            <!-- Modal Content Wrapper -->
            <div class="flex items-center justify-center min-h-screen p-4 md:p-6 z-50 relative">
                <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden x-transition">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Add New Property</h3>
                        <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('landlord.properties.store') }}" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Property Name</label>
                            <input type="text" name="name" required placeholder="e.g. Oakridge Heights, Apt 4" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Property Type</label>
                                <select name="type" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white">
                                    <option value="house">House</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="commercial">Commercial</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">City</label>
                                <input type="text" name="city" required placeholder="e.g. London" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Address</label>
                            <input type="text" name="address" required placeholder="e.g. 123 High Street" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description (Optional)</label>
                            <textarea name="description" placeholder="Provide extra details about the property..." rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white"></textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end space-x-3">
                            <button type="button" @click="addModalOpen = false" class="px-4.5 py-2.5 border border-slate-200 dark:border-slate-850 text-sm font-semibold text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-950">Cancel</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/10">Add Property</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT PROPERTY MODAL -->
        <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModalOpen = false"></div>

            <!-- Modal Content Wrapper -->
            <div class="flex items-center justify-center min-h-screen p-4 md:p-6 z-50 relative">
                <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden x-transition">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Edit Property</h3>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="`{{ url('/landlord/properties') }}/${editProp.id}`" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Property Name</label>
                            <input type="text" name="name" required x-model="editProp.name" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Property Type</label>
                                <select name="type" required x-model="editProp.type" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white">
                                    <option value="house">House</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="commercial">Commercial</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">City</label>
                                <input type="text" name="city" required x-model="editProp.city" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Address</label>
                            <input type="text" name="address" required x-model="editProp.address" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description (Optional)</label>
                            <textarea name="description" x-model="editProp.description" rows="3" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white"></textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end space-x-3">
                            <button type="button" @click="editModalOpen = false" class="px-4.5 py-2.5 border border-slate-200 dark:border-slate-850 text-sm font-semibold text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-950">Cancel</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/10">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-landlord-layout>
