<x-landlord-layout>
    <x-slot:title>{{ $property->name }} - LandForDays</x-slot:title>

    <div x-data="{ 
        addUnitModalOpen: false, 
        editUnitModalOpen: false, 
        editUnitObj: { id: '', unit_number: '', status: 'vacant', bedrooms: 1 },
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
        <!-- Back navigation link -->
        <div class="mb-6">
            <a href="{{ route('landlord.properties.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Properties
            </a>
        </div>

        <!-- Property Overview Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
            <div class="h-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900">
                                {{ $property->type }}
                            </span>
                            <span class="text-xs text-slate-400">Created {{ $property->created_at->diffForHumans() }}</span>
                        </div>
                        <h1 class="mt-2.5 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                            {{ $property->name }}
                        </h1>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 flex items-center">
                            <svg class="flex-shrink-0 mr-2 h-4 w-4 text-slate-450" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $property->address }}, {{ $property->city }}
                        </p>
                    </div>
                    <div>
                        <button @click="addUnitModalOpen = true" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all duration-200 cursor-pointer">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Add New Unit
                        </button>
                    </div>
                </div>

                @if ($property->description)
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Description</h4>
                        <p class="text-sm text-slate-650 dark:text-slate-350 leading-relaxed max-w-3xl">
                            {{ $property->description }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Units Section Title -->
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Active Units ({{ $property->units->count() }})</h2>
        </div>

        @if ($property->units->isEmpty())
            <!-- Units Empty State -->
            <div class="p-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm max-w-2xl mx-auto">
                <div class="p-4 bg-indigo-50 dark:bg-indigo-950/30 rounded-full w-20 h-20 flex items-center justify-center mx-auto text-indigo-600 dark:text-indigo-400 mb-6">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white">No Units Created</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    This property has no units listed yet. Create units (e.g. "Suite A", "Room 304") to invite tenants.
                </p>
                <div class="mt-8">
                    <button @click="addUnitModalOpen = true" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md">
                        Add Your First Unit
                    </button>
                </div>
            </div>
        @else
            <!-- Search Bar -->
            <div class="mb-6 relative max-w-md select-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Search units by number, status, bedrooms..." 
                    class="block w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm placeholder-slate-400 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm outline-none"
                />
            </div>

            <!-- Units Grid -->
            <div x-ref="listContainer" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($property->units as $unit)
                    <div data-search="Unit {{ $unit->unit_number }} {{ $unit->status }} {{ $unit->bedrooms }} Bedrooms ID: #00{{ $unit->id }}" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 relative group">
                        
                        <!-- Unit identifier -->
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    Unit {{ $unit->unit_number }}
                                </h3>
                                <div class="flex items-center space-x-2 mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="font-medium">ID: #00{{ $unit->id }}</span>
                                    <span class="text-slate-300 dark:text-slate-700">•</span>
                                    <span class="inline-flex items-center gap-1 font-medium bg-slate-50 dark:bg-slate-950 px-2 py-0.5 rounded-md border border-slate-100 dark:border-slate-850">
                                        <svg class="h-3.5 w-3.5 text-indigo-500 dark:text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 4v16" />
                                            <path d="M2 8h18M2 12h20" />
                                            <path d="M22 4v16" />
                                            <path d="M2 17h20" />
                                            <path d="M6 8v4" />
                                        </svg>
                                        {{ $unit->bedrooms }} {{ Str::plural('Bed', $unit->bedrooms) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            @if ($unit->status === 'vacant')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900 uppercase tracking-wider">
                                    Vacant
                                </span>
                            @elseif ($unit->status === 'occupied')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900 uppercase tracking-wider">
                                    Occupied
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-755 dark:text-amber-400 border border-amber-100 dark:border-amber-900 uppercase tracking-wider">
                                    Maintenance
                                </span>
                            @endif
                        </div>

                        <!-- Micro Info -->
                        <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
                            <span>Tenant: <span class="font-semibold text-slate-700 dark:text-slate-350">{{ $unit->status === 'occupied' ? 'Assigned' : 'None' }}</span></span>
                            <span>Updated {{ $unit->updated_at->diffForHumans() }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center space-x-2.5 select-none">
                            <button @click="editUnitObj = { id: '{{ $unit->id }}', unit_number: '{{ addslashes($unit->unit_number) }}', status: '{{ $unit->status }}', bedrooms: '{{ $unit->bedrooms }}' }; editUnitModalOpen = true;" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-650 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white transition-all duration-200 cursor-pointer">
                                Edit Status
                            </button>

                            <form method="POST" action="{{ route('landlord.units.destroy', $unit->id) }}" onsubmit="return confirm('Are you sure you want to delete this unit?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-500 bg-slate-50 dark:bg-slate-900 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Delete Unit">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
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
                <h4 class="text-lg font-bold text-slate-900 dark:text-white">No units match search</h4>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Try checking your spelling or search terms.</p>
            </div>

            <!-- Pagination Footer -->
            <div x-show="totalPages > 1" class="mt-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center justify-between select-none" style="display: none;" x-cloak>
                <div class="text-xs text-slate-500">
                    Showing page <span class="font-bold text-slate-700 dark:text-slate-350" x-text="page"></span> of <span class="font-bold text-slate-700 dark:text-slate-350" x-text="totalPages"></span> (<span x-text="totalMatching"></span> matching units)
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

        <!-- ADD UNIT MODAL -->
        <div x-show="addUnitModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addUnitModalOpen = false"></div>

            <!-- Modal Content Wrapper -->
            <div class="flex items-center justify-center min-h-screen p-4 md:p-6 z-50 relative">
                <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-sm w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden x-transition">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Add New Unit</h3>
                        <button @click="addUnitModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('landlord.units.store') }}" class="p-6 space-y-4">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->id }}" />
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Unit Number / Code</label>
                            <input type="text" name="unit_number" required placeholder="e.g. Suite A, Apartment 1, Room 10" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Amount of Bedrooms</label>
                            <input type="number" name="bedrooms" min="0" max="100" value="1" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Baseline Status</label>
                            <select name="status" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white">
                                <option value="vacant" selected>Vacant</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end space-x-3">
                            <button type="button" @click="addUnitModalOpen = false" class="px-4.5 py-2.5 border border-slate-200 dark:border-slate-850 text-sm font-semibold text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-950">Cancel</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/10">Create Unit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT UNIT MODAL -->
        <div x-show="editUnitModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editUnitModalOpen = false"></div>

            <!-- Modal Content Wrapper -->
            <div class="flex items-center justify-center min-h-screen p-4 md:p-6 z-50 relative">
                <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-sm w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden x-transition">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Edit Unit Status</h3>
                        <button @click="editUnitModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="`{{ url('/landlord/units') }}/${editUnitObj.id}`" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Unit Number / Code</label>
                            <input type="text" name="unit_number" required x-model="editUnitObj.unit_number" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Amount of Bedrooms</label>
                            <input type="number" name="bedrooms" min="0" max="100" required x-model="editUnitObj.bedrooms" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Unit Status</label>
                            <select name="status" required x-model="editUnitObj.status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-white">
                                <option value="vacant">Vacant</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end space-x-3">
                            <button type="button" @click="editUnitModalOpen = false" class="px-4.5 py-2.5 border border-slate-200 dark:border-slate-850 text-sm font-semibold text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-950">Cancel</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/10">Save Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-landlord-layout>
