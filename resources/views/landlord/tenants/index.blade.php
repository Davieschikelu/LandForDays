<x-landlord-layout title="Tenants Registry - LandForDays">
    <div x-data="{ 
        activeTab: 'all', 
        inviteModalOpen: false,
        copiedToken: '',
        copyStatus: false
    }" class="space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Tenants</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                        {{ count($leases) }} Active
                    </span>
                    @if(count($invites) > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                            {{ count($invites) }} Pending Invites
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Manage tenant leases, track rent statuses, and distribute digital onboarding keys.
                </p>
            </div>
            
            <button @click="inviteModalOpen = true" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/10 transition-all duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Invite Tenant
            </button>
        </div>

        <!-- Session Copy Warning Banner -->
        @if (session('success') && strpos(session('success'), 'Invite Link:') !== false)
            @php
                $parts = explode('Invite Link: ', session('success'));
                $message = $parts[0];
                $link = $parts[1] ?? '';
            @endphp
            <div class="p-6 bg-indigo-550 bg-indigo-600 rounded-3xl text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="space-y-1">
                    <h3 class="font-bold text-lg">Digital Invitation Generated!</h3>
                    <p class="text-xs text-indigo-100 max-w-2xl">
                        Copy the link below and send it to your tenant. They can click it to complete registration and sign their lease instantly.
                    </p>
                    <div class="mt-3 bg-slate-950/40 p-2.5 rounded-xl font-mono text-xs break-all select-all border border-indigo-400/20">
                        {{ $link }}
                    </div>
                </div>
                <div>
                    <button 
                        @click="
                            navigator.clipboard.writeText('{{ $link }}');
                            copyStatus = true;
                            setTimeout(() => copyStatus = false, 3000);
                        "
                        class="w-full md:w-auto inline-flex items-center justify-center px-5 py-3 bg-white text-indigo-600 font-semibold rounded-2xl hover:bg-indigo-50 shadow-sm transition-all text-sm"
                    >
                        <svg x-show="!copyStatus" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <svg x-show="copyStatus" class="mr-2 h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copyStatus ? 'Copied to Clipboard!' : 'Copy Invite Link'"></span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Dynamic Filter Pill Tabs -->
        <div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-800 pb-3">
            <button 
                @click="activeTab = 'all'" 
                :class="activeTab === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-bold' : 'bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-450 hover:bg-slate-200 dark:hover:bg-slate-800/80'" 
                class="px-4 py-2 rounded-full text-xs font-semibold transition-all"
            >
                All Tenants ({{ count($leases) }})
            </button>
            <button 
                @click="activeTab = 'pending'" 
                :class="activeTab === 'pending' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-bold' : 'bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-450 hover:bg-slate-200 dark:hover:bg-slate-800/80'" 
                class="px-4 py-2 rounded-full text-xs font-semibold transition-all"
            >
                Pending Invites ({{ count($invites) }})
            </button>
        </div>

        <!-- Primary Registry Card Container -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <!-- Active Tenants List -->
            <div 
                x-show="activeTab === 'all'" 
                class="divide-y divide-slate-150 dark:divide-slate-800"
                x-data="{
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
                            const container = this.$refs.tableBody;
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
                }"
            >
                @if(count($leases) === 0)
                    <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                        <svg class="mx-auto h-12 w-12 text-slate-350 dark:text-slate-655" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">No active tenants</h3>
                        <p class="mt-1 text-xs text-slate-400">
                            Invite a new tenant to generate onboarding credentials.
                        </p>
                        <div class="mt-6">
                            <button @click="inviteModalOpen = true" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700">
                                Send First Invitation
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Search Bar -->
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 relative">
                        <div class="absolute inset-y-0 left-6 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            x-model="search" 
                            placeholder="Search active tenants by name, email, property, unit..." 
                            class="block w-full max-w-md pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-sm placeholder-slate-400 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm outline-none"
                        />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-150 dark:divide-slate-800 text-left">
                            <thead class="bg-slate-50/50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Tenant</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Property & Unit</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Agreement Verification</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Monthly Rent</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Lease Term</th>
                                </tr>
                            </thead>
                            <tbody x-ref="tableBody" class="divide-y divide-slate-150 dark:divide-slate-800 bg-white dark:bg-slate-900">
                                @foreach($leases as $lease)
                                    <tr data-search="{{ $lease->tenant->name }} {{ $lease->tenant->email }} {{ $lease->unit->property->name }} Unit {{ $lease->unit->unit_number }}" class="hover:bg-slate-50/40 dark:hover:bg-slate-950/20 transition-all">
                                        <!-- Tenant Stack -->
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-10 w-10 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">
                                                    {{ substr($lease->tenant->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                                        {{ $lease->tenant->name }}
                                                    </div>
                                                    <div class="text-xs text-slate-400">
                                                        {{ $lease->tenant->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Linked Unit -->
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                                    {{ $lease->unit->property->name }}
                                                </div>
                                                <div class="text-xs text-slate-400">
                                                    Unit {{ $lease->unit->unit_number }}
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Agreement Verification Badge -->
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            @if ($lease->is_confirmed)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-550/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/20">
                                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span>
                                                    Confirmed
                                                </span>
                                                @if ($lease->signed_agreement_path)
                                                    <a href="{{ route('agreements.download', ['type' => 'signed', 'id' => $lease->id]) }}" class="block mt-1.5 text-[10px] font-semibold text-indigo-500 hover:text-indigo-650 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline">Download signed doc</a>
                                                @endif
                                            @elseif ($lease->signed_agreement_path)
                                                <div class="space-y-1.5">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-500/20 animate-pulse">
                                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span>
                                                        Pending Review
                                                    </span>
                                                    <div class="flex items-center space-x-1.5 text-[10px] font-bold text-slate-500">
                                                        <a href="{{ route('agreements.download', ['type' => 'signed', 'id' => $lease->id]) }}" class="text-indigo-500 hover:text-indigo-650 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline">
                                                            Download signed doc
                                                        </a>
                                                        <span>•</span>
                                                        <form action="{{ route('landlord.leases.confirm-tenancy', $lease->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="action" value="confirm">
                                                            <button type="submit" class="text-emerald-500 hover:text-emerald-400 cursor-pointer">Confirm</button>
                                                        </form>
                                                        <span>•</span>
                                                        <form action="{{ route('landlord.leases.confirm-tenancy', $lease->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" class="text-rose-500 hover:text-rose-400 cursor-pointer">Reject</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-500 dark:bg-slate-500/20 dark:text-slate-400 border border-slate-500/20">
                                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-slate-400"></span>
                                                    Unconfirmed (Awaiting Sign)
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <!-- Monthly Rent -->
                                        <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-slate-900 dark:text-white">
                                            ₦{{ number_format($lease->monthly_rent, 2) }}
                                        </td>
                                        
                                        <!-- Lease Term -->
                                        <td class="px-6 py-5 whitespace-nowrap text-xs text-slate-400">
                                            <div>
                                                {{ $lease->start_date->format('M d, Y') }} &rarr;
                                            </div>
                                            <div>
                                                {{ $lease->end_date->format('M d, Y') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty Search Results State -->
                    <div x-show="totalMatching === 0" class="p-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm max-w-2xl mx-auto mt-6" style="display: none;" x-cloak>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">No active tenants match search.</p>
                    </div>

                    <!-- Pagination Footer -->
                    <div x-show="totalPages > 1" class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between select-none" style="display: none;" x-cloak>
                        <div class="text-xs text-slate-500">
                            Showing page <span class="font-bold text-slate-700 dark:text-slate-350" x-text="page"></span> of <span class="font-bold text-slate-700 dark:text-slate-350" x-text="totalPages"></span> (<span x-text="totalMatching"></span> matching tenants)
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
            </div>

            <!-- Pending Invitations List -->
            <div 
                x-show="activeTab === 'pending'" 
                class="divide-y divide-slate-150 dark:divide-slate-800" 
                style="display: none;"
                x-data="{
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
                            const container = this.$refs.tableBody;
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
                }"
            >
                @if(count($invites) === 0)
                    <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                        <svg class="mx-auto h-12 w-12 text-slate-350 dark:text-slate-655" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">No pending invitations</h3>
                        <p class="mt-1 text-xs text-slate-400">
                            Create a new digital invite token when onboarding a tenant.
                        </p>
                    </div>
                @else
                    <!-- Search Bar -->
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 relative">
                        <div class="absolute inset-y-0 left-6 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            x-model="search" 
                            placeholder="Search invites by email, property, unit..." 
                            class="block w-full max-w-md pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-sm placeholder-slate-400 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm outline-none"
                        />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-150 dark:divide-slate-800 text-left">
                            <thead class="bg-slate-50/50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Property & Unit</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Lease Value</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Expires At</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-550 dark:text-slate-400 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody x-ref="tableBody" class="divide-y divide-slate-150 dark:divide-slate-800 bg-white dark:bg-slate-900">
                                @foreach($invites as $invite)
                                    <tr data-search="{{ $invite->email }} {{ $invite->unit->property->name }} Unit {{ $invite->unit->unit_number }}" class="hover:bg-slate-50/40 dark:hover:bg-slate-950/20 transition-all">
                                        <!-- Email Stack -->
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs select-none">
                                                    ✉
                                                </div>
                                                <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                                    {{ $invite->email }}
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Unit -->
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                                    {{ $invite->unit->property->name }}
                                                </div>
                                                <div class="text-xs text-slate-400">
                                                    Unit {{ $invite->unit->unit_number }}
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Monthly Rent -->
                                        <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-slate-900 dark:text-white">
                                            ₦{{ number_format($invite->monthly_rent, 2) }}/mo
                                        </td>
                                        
                                        <!-- Expiration Date -->
                                        <td class="px-6 py-5 whitespace-nowrap text-xs text-slate-400">
                                            {{ $invite->expires_at ? $invite->expires_at->format('M d, Y H:i') : 'N/A' }}
                                        </td>
                                        
                                        <!-- Revocation Button -->
                                        <td class="px-6 py-5 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <!-- Copy Link Direct Action -->
                                                <button 
                                                    type="button"
                                                    @click="
                                                        navigator.clipboard.writeText('{{ route('tenant.onboard.show', $invite->token) }}');
                                                        copiedToken = '{{ $invite->token }}';
                                                        setTimeout(() => copiedToken = '', 2000);
                                                    "
                                                    class="p-2 text-slate-400 hover:text-indigo-650 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors cursor-pointer"
                                                    title="Copy Onboard Link"
                                                >
                                                    <svg x-show="copiedToken !== '{{ $invite->token }}'" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    <span x-show="copiedToken === '{{ $invite->token }}'" class="text-xs text-emerald-600 font-bold" style="display: none;">Copied!</span>
                                                </button>
 
                                                <!-- Revoke Form -->
                                                <form action="{{ route('landlord.tenants.invite.destroy', $invite->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke this invitation?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors cursor-pointer" title="Revoke invite">
                                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty Search Results State -->
                    <div x-show="totalMatching === 0" class="p-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm max-w-2xl mx-auto mt-6" style="display: none;" x-cloak>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">No pending invitations match search.</p>
                    </div>

                    <!-- Pagination Footer -->
                    <div x-show="totalPages > 1" class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between select-none" style="display: none;" x-cloak>
                        <div class="text-xs text-slate-500">
                            Showing page <span class="font-bold text-slate-700 dark:text-slate-350" x-text="page"></span> of <span class="font-bold text-slate-700 dark:text-slate-350" x-text="totalPages"></span> (<span x-text="totalMatching"></span> matching invites)
                        </div>
                        <div class="flex items-center space-x-2">
                            <button 
                                type="button"
                                @click="if (page > 1) { page-- }" 
                                :disabled="page === 1"
                                class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 text-xs font-semibold bg-white dark:bg-slate-900 text-slate-655 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-850 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all"
                            >
                                Previous
                            </button>
                            <button 
                                type="button"
                                @click="if (page < totalPages) { page++ }" 
                                :disabled="page === totalPages"
                                class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 text-xs font-semibold bg-white dark:bg-slate-900 text-slate-655 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-850 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Alpine Modal: Send Invite Form -->
        <div 
            x-show="inviteModalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto"
            style="display: none;"
            x-transition
        >
            <div 
                @click.away="inviteModalOpen = false" 
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl animate-in fade-in zoom-in-95 duration-200 max-h-[90vh] overflow-y-auto"
            >
                <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Create Tenant Invite</h2>
                    <button @click="inviteModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('landlord.tenants.invite') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    
                    <!-- Vacant Unit Selection -->
                    <div>
                        <label for="unit_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Select Vacant Unit</label>
                        <select 
                            name="unit_id" 
                            id="unit_id" 
                            required 
                            class="block w-full rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-950 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3"
                        >
                            <option value="">-- Choose Unit --</option>
                            @foreach($properties as $property)
                                <optgroup label="{{ $property->name }}">
                                    @foreach($property->units as $unit)
                                        @if($unit->status === 'vacant')
                                            <option value="{{ $unit->id }}">Unit {{ $unit->unit_number }} (Vacant)</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @if($vacantUnits->count() === 0)
                            <p class="mt-1 text-xs text-rose-500">
                                ⚠️ No vacant units available. Please create or update a unit to vacant first.
                            </p>
                        @endif
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tenant Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            required 
                            placeholder="tenant@example.com"
                            class="block w-full rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-950 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3"
                        >
                    </div>

                    <!-- Monthly Rent -->
                    <div>
                        <label for="monthly_rent" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Monthly Rent (₦)</label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="monthly_rent" 
                            id="monthly_rent" 
                            required 
                            placeholder="1200.00"
                            class="block w-full rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-950 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3"
                        >
                    </div>

                    <!-- Tenancy Agreement Template (Optional) -->
                    <div>
                        <label for="agreement_template" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tenancy Agreement Form (Optional)</label>
                        <input 
                            type="file" 
                            name="agreement_template" 
                            id="agreement_template" 
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="block w-full rounded-xl border border-slate-200 dark:border-slate-850 bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3"
                        >
                        <p class="mt-1 text-[10px] text-slate-450 leading-relaxed">
                            Upload a blank agreement template (PDF, Word, or Image). The tenant will download, sign, and upload this document back to activate their dashboard.
                        </p>
                    </div>

                    <!-- Dates Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Lease Start Date</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                id="start_date" 
                                required 
                                class="block w-full rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-950 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3"
                            >
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Lease End Date</label>
                            <input 
                                type="date" 
                                name="end_date" 
                                id="end_date" 
                                required 
                                class="block w-full rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-950 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3"
                            >
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="pt-4 flex items-center justify-end space-x-3">
                        <button 
                            type="button" 
                            @click="inviteModalOpen = false" 
                            class="px-5 py-2.5 text-sm font-semibold rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition-all duration-200"
                        >
                            Generate Invite Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</x-landlord-layout>
