<x-landlord-layout title="Maintenance Board - LandForDays">
    <div x-data="{ 
        detailModalOpen: false,
        activeTicket: {
            id: '',
            property_name: '',
            unit_number: '',
            tenant_name: '',
            tenant_email: '',
            category: '',
            priority: '',
            description: '',
            status: '',
            photo_url: '',
            notes: '',
            created_at: ''
        },
        openDetail(ticket) {
            this.activeTicket = ticket;
            this.detailModalOpen = true;
        },

        // Search & Pagination State
        search: '',
        pageOpen: 1,
        pageInProgress: 1,
        pageResolved: 1,
        perPage: 10,
        totalOpenMatching: 0,
        totalInProgressMatching: 0,
        totalResolvedMatching: 0,

        init() {
            this.updateColumns();
            this.$watch('search', () => { 
                this.pageOpen = 1; 
                this.pageInProgress = 1; 
                this.pageResolved = 1; 
                this.updateColumns(); 
            });
            this.$watch('pageOpen', () => { this.updateColumns(); });
            this.$watch('pageInProgress', () => { this.updateColumns(); });
            this.$watch('pageResolved', () => { this.updateColumns(); });
        },

        updateColumns() {
            this.$nextTick(() => {
                const query = this.search.toLowerCase().trim();
                
                const filterCol = (colRefName, pageNum) => {
                    const col = this.$refs[colRefName];
                    if (!col) return 0;
                    
                    const cards = col.querySelectorAll('[data-search]');
                    let matching = [];
                    cards.forEach(card => {
                        const text = card.getAttribute('data-search').toLowerCase();
                        if (text.includes(query)) {
                            matching.push(card);
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    const start = (pageNum - 1) * this.perPage;
                    const end = start + this.perPage;
                    matching.forEach((card, idx) => {
                        if (idx >= start && idx < end) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    return matching.length;
                };
                
                this.totalOpenMatching = filterCol('colOpen', this.pageOpen);
                this.totalInProgressMatching = filterCol('colInProgress', this.pageInProgress);
                this.totalResolvedMatching = filterCol('colResolved', this.pageResolved);
            });
        },

        totalPages(totalCount) {
            return Math.ceil(totalCount / this.perPage) || 1;
        }
    }" class="space-y-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Maintenance Tickets</h1>
                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Manage property issues, track plumber/electrician logs, and communicate updates to tenants.</p>
            </div>
            
            <!-- Quick Stats Summary Bar -->
            <div class="flex space-x-3">
                <div class="px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center space-x-2.5 shadow-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300" x-text="search ? totalOpenMatching + ' Open' : '{{ $open->count() }} Open'">{{ $open->count() }} Open</span>
                </div>
                <div class="px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center space-x-2.5 shadow-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300" x-text="search ? totalInProgressMatching + ' Active' : '{{ $inProgress->count() }} Active'">{{ $inProgress->count() }} Active</span>
                </div>
                <div class="px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center space-x-2.5 shadow-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300" x-text="search ? totalResolvedMatching + ' Resolved' : '{{ $resolved->count() }} Resolved'">{{ $resolved->count() }} Resolved</span>
                </div>
            </div>
        </div>

        <!-- Global Search Bar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-4 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative w-full max-w-lg">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Search maintenance by property, unit, description, priority, category..." 
                    class="block w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs placeholder-slate-400 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                />
            </div>
            <div class="text-xs text-slate-450 dark:text-slate-400">
                Lanes will filter instantly as you type. Capped at 10 tickets per page.
            </div>
        </div>

        <!-- Kanban Board Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- COLUMN 1: OPEN / NEW -->
            <div class="flex flex-col bg-slate-100/70 dark:bg-slate-900/40 p-4 rounded-3xl border border-slate-200/50 dark:border-slate-800/40 min-h-[600px]">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div class="flex items-center space-x-2">
                        <span class="h-3 w-3 rounded-full bg-rose-500"></span>
                        <h2 class="text-md font-bold text-slate-800 dark:text-white uppercase tracking-wider font-outfit">Open / Pending</h2>
                    </div>
                    <span class="px-2.5 py-0.5 text-xs font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-full" x-text="search ? totalOpenMatching : {{ $open->count() }}">
                        {{ $open->count() }}
                    </span>
                </div>
                
                <div x-ref="colOpen" class="flex-1 space-y-4">
                    @forelse($open as $ticket)
                        @include('landlord.maintenance._card', ['ticket' => $ticket])
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 px-4 border border-dashed border-slate-300 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-600 select-none">
                            <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span class="text-xs font-semibold">No open requests</span>
                        </div>
                    @endforelse
                </div>

                <!-- Empty Search State for Open -->
                @if($open->isNotEmpty())
                    <div x-show="totalOpenMatching === 0" class="py-12 px-4 text-center text-slate-400 dark:text-slate-500" style="display: none;" x-cloak>
                        <p class="text-xs font-semibold">No matching open tickets.</p>
                    </div>
                @endif

                <!-- Column 1 Pagination Footer -->
                <div x-show="totalPages(totalOpenMatching) > 1" class="mt-4 pt-3 border-t border-slate-200/55 dark:border-slate-800/60 flex items-center justify-between select-none" style="display: none;" x-cloak>
                    <span class="text-[10px] text-slate-500 font-bold">Page <span x-text="pageOpen"></span> of <span x-text="totalPages(totalOpenMatching)"></span></span>
                    <div class="flex items-center space-x-1">
                        <button type="button" @click="if (pageOpen > 1) pageOpen--" :disabled="pageOpen === 1" class="px-2 py-1 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-850 text-[10px] font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 disabled:opacity-40 disabled:cursor-not-allowed">Prev</button>
                        <button type="button" @click="if (pageOpen < totalPages(totalOpenMatching)) pageOpen++" :disabled="pageOpen === totalPages(totalOpenMatching)" class="px-2 py-1 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-850 text-[10px] font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 disabled:opacity-40 disabled:cursor-not-allowed">Next</button>
                    </div>
                </div>
            </div>

            <!-- COLUMN 2: IN PROGRESS -->
            <div class="flex flex-col bg-slate-100/70 dark:bg-slate-900/40 p-4 rounded-3xl border border-slate-200/50 dark:border-slate-800/40 min-h-[600px]">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div class="flex items-center space-x-2">
                        <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                        <h2 class="text-md font-bold text-slate-800 dark:text-white uppercase tracking-wider font-outfit">In Progress</h2>
                    </div>
                    <span class="px-2.5 py-0.5 text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-full" x-text="search ? totalInProgressMatching : {{ $inProgress->count() }}">
                        {{ $inProgress->count() }}
                    </span>
                </div>
                
                <div x-ref="colInProgress" class="flex-1 space-y-4">
                    @forelse($inProgress as $ticket)
                        @include('landlord.maintenance._card', ['ticket' => $ticket])
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 px-4 border border-dashed border-slate-300 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-600 select-none">
                            <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            </svg>
                            <span class="text-xs font-semibold">No active issues</span>
                        </div>
                    @endforelse
                </div>

                <!-- Empty Search State for In Progress -->
                @if($inProgress->isNotEmpty())
                    <div x-show="totalInProgressMatching === 0" class="py-12 px-4 text-center text-slate-400 dark:text-slate-500" style="display: none;" x-cloak>
                        <p class="text-xs font-semibold">No matching in-progress tickets.</p>
                    </div>
                @endif

                <!-- Column 2 Pagination Footer -->
                <div x-show="totalPages(totalInProgressMatching) > 1" class="mt-4 pt-3 border-t border-slate-200/55 dark:border-slate-800/60 flex items-center justify-between select-none" style="display: none;" x-cloak>
                    <span class="text-[10px] text-slate-500 font-bold">Page <span x-text="pageInProgress"></span> of <span x-text="totalPages(totalInProgressMatching)"></span></span>
                    <div class="flex items-center space-x-1">
                        <button type="button" @click="if (pageInProgress > 1) pageInProgress--" :disabled="pageInProgress === 1" class="px-2 py-1 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-850 text-[10px] font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 disabled:opacity-40 disabled:cursor-not-allowed">Prev</button>
                        <button type="button" @click="if (pageInProgress < totalPages(totalInProgressMatching)) pageInProgress++" :disabled="pageInProgress === totalPages(totalInProgressMatching)" class="px-2 py-1 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-850 text-[10px] font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 disabled:opacity-40 disabled:cursor-not-allowed">Next</button>
                    </div>
                </div>
            </div>

            <!-- COLUMN 3: RESOLVED -->
            <div class="flex flex-col bg-slate-100/70 dark:bg-slate-900/40 p-4 rounded-3xl border border-slate-200/50 dark:border-slate-800/40 min-h-[600px]">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div class="flex items-center space-x-2">
                        <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                        <h2 class="text-md font-bold text-slate-800 dark:text-white uppercase tracking-wider font-outfit">Resolved</h2>
                    </div>
                    <span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-full" x-text="search ? totalResolvedMatching : {{ $resolved->count() }}">
                        {{ $resolved->count() }}
                    </span>
                </div>
                
                <div x-ref="colResolved" class="flex-1 space-y-4">
                    @forelse($resolved as $ticket)
                        @include('landlord.maintenance._card', ['ticket' => $ticket])
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 px-4 border border-dashed border-slate-300 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-600 select-none">
                            <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs font-semibold">No resolved tickets</span>
                        </div>
                    @endforelse
                </div>

                <!-- Empty Search State for Resolved -->
                @if($resolved->isNotEmpty())
                    <div x-show="totalResolvedMatching === 0" class="py-12 px-4 text-center text-slate-400 dark:text-slate-500" style="display: none;" x-cloak>
                        <p class="text-xs font-semibold">No matching resolved tickets.</p>
                    </div>
                @endif

                <!-- Column 3 Pagination Footer -->
                <div x-show="totalPages(totalResolvedMatching) > 1" class="mt-4 pt-3 border-t border-slate-200/55 dark:border-slate-800/60 flex items-center justify-between select-none" style="display: none;" x-cloak>
                    <span class="text-[10px] text-slate-500 font-bold">Page <span x-text="pageResolved"></span> of <span x-text="totalPages(totalResolvedMatching)"></span></span>
                    <div class="flex items-center space-x-1">
                        <button type="button" @click="if (pageResolved > 1) pageResolved--" :disabled="pageResolved === 1" class="px-2 py-1 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-850 text-[10px] font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 disabled:opacity-40 disabled:cursor-not-allowed">Prev</button>
                        <button type="button" @click="if (pageResolved < totalPages(totalResolvedMatching)) pageResolved++" :disabled="pageResolved === totalPages(totalResolvedMatching)" class="px-2 py-1 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-850 text-[10px] font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 disabled:opacity-40 disabled:cursor-not-allowed">Next</button>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- HIGH-FIDELITY INTERACTIVE DETAIL MODAL -->
        <div x-show="detailModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="detailModalOpen = false"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative max-w-2xl w-full bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-5 bg-gradient-to-r from-indigo-900 to-indigo-950 text-white flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold">Ticket Details</h3>
                            <p class="text-xs text-indigo-200 mt-0.5">
                                <span x-text="activeTicket.property_name"></span> - Unit <span x-text="activeTicket.unit_number"></span>
                            </p>
                        </div>
                        <button @click="detailModalOpen = false" class="text-indigo-200 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body / Form -->
                    <form :action="'{{ route('landlord.maintenance.index') }}/' + activeTicket.id" method="POST" class="p-6 space-y-6 flex-1">
                        @csrf
                        @method('PUT')
                        
                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                            <div>
                                <span class="text-xs text-slate-400 block">Reported By</span>
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200" x-text="activeTicket.tenant_name"></span>
                                <span class="text-xs text-slate-500 block truncate" x-text="activeTicket.tenant_email"></span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Date Reported</span>
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200" x-text="activeTicket.created_at"></span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Category</span>
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 text-xs font-bold rounded-lg capitalize bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    <span x-text="activeTicket.category"></span>
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Priority</span>
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 text-xs font-bold rounded-lg capitalize"
                                    :class="{
                                        'bg-blue-500/10 text-blue-600 dark:text-blue-400': activeTicket.priority === 'low',
                                        'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400': activeTicket.priority === 'medium',
                                        'bg-orange-500/10 text-orange-600 dark:text-orange-400': activeTicket.priority === 'high',
                                        'bg-red-500/10 text-red-600 dark:text-red-400': activeTicket.priority === 'emergency'
                                    }">
                                    <span x-text="activeTicket.priority"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Ticket Description -->
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tenant Description</span>
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-mono" x-text="activeTicket.description"></div>
                        </div>

                        <!-- Photo Attachment -->
                        <div x-show="activeTicket.photo_url" class="space-y-1.5">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Photo Attachment</span>
                            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden max-h-64 bg-slate-950 flex items-center justify-center">
                                <img :src="activeTicket.photo_url" class="max-h-64 object-contain" alt="Maintenance Photo Attachment">
                            </div>
                        </div>

                        <!-- Status Slider dropdown -->
                        <div class="space-y-1.5">
                            <label for="status" class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Update Ticket Status</label>
                            <select id="status" name="status" x-model="activeTicket.status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                <option value="open">Open / Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>

                        <!-- Landlord Notes (Communication portal) -->
                        <div class="space-y-1.5">
                            <label for="notes" class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Landlord Notes & Timeline Updates</label>
                            <textarea id="notes" name="notes" x-model="activeTicket.notes" rows="3" placeholder="Provide notes, e.g. 'Plumber is dispatched for tomorrow 9 AM...'" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all leading-relaxed"></textarea>
                            <span class="text-[11px] text-slate-400 block">Note: These comments are immediately visible to the tenant on their dashboard timeline.</span>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2 flex justify-end space-x-3">
                            <button type="button" @click="detailModalOpen = false" class="px-5 py-3 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-950 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-bold shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/35 transition-all">
                                Save Updates
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-landlord-layout>
