<x-landlord-layout>
    <div class="space-y-8" x-data="{ openRecordModal: false, openBankModal: false }">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-blue-400 font-outfit">
                    Financial Ledger
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Centralized rent transactions ledger, automatic card settlements, and manual offline billing entry.
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <button 
                    @click="openBankModal = true"
                    class="inline-flex items-center justify-center px-5 py-3 border border-slate-800 text-sm font-semibold rounded-xl text-slate-350 hover:text-white hover:bg-slate-905 hover:bg-slate-900 transition-all duration-200 cursor-pointer"
                >
                    🏛️ <span class="ml-2">Bank Details Settings</span>
                </button>
                <button 
                    @click="openRecordModal = true"
                    class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition-all duration-200 cursor-pointer"
                >
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Record Manual Payment
                </button>
            </div>
        </div>

        <!-- Session Message Alert -->
        @if (session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl flex items-center space-x-3">
                <svg class="h-5 w-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        <!-- KPI Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- ── Total Collected ── Emerald accent -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 border-l-4 border-l-emerald-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Collected</span>
                    <span class="p-2.5 bg-emerald-100 dark:bg-emerald-500/20 rounded-xl text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-outfit tracking-tight">
                    ₦{{ number_format($totalCollected, 2) }}
                </h3>
                <div class="mt-3 flex items-center gap-2 flex-wrap">
                    <span class="flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                        ✓ Verified
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Successfully settled on-platform</span>
                </div>
            </div>

            <!-- ── Expected Monthly ── Indigo accent -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 border-l-4 border-l-indigo-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Expected Monthly</span>
                    <span class="p-2.5 bg-indigo-100 dark:bg-indigo-500/20 rounded-xl text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-outfit tracking-tight">
                    ₦{{ number_format($upcomingRent, 2) }}
                </h3>
                <div class="mt-3 flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30">
                        {{ $activeLeases->count() }} Active Leases
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Combined monthly obligation</span>
                </div>
            </div>

            <!-- ── Pending / Overdue ── Amber accent -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 border-l-4 border-l-amber-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pending / Overdue</span>
                    <span class="p-2.5 bg-amber-100 dark:bg-amber-500/20 rounded-xl text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-outfit tracking-tight">
                    ₦{{ number_format($overdueBalance, 2) }}
                </h3>
                <div class="mt-3 flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30">
                        ⚠ Awaiting Action
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Requires landlord verification</span>
                </div>
            </div>

        </div>

        <!-- Ledger Table Panel -->

        <!-- Ledger Table Panel -->

        <div 
            class="bg-slate-900/40 backdrop-blur-md border border-slate-800 rounded-2xl overflow-hidden"
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
            <div class="px-6 py-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-white font-outfit">Transaction Ledger</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Centralized logs of all paid rents & invoices</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-800 text-slate-300 rounded-full w-fit">
                    {{ $payments->count() }} records
                </span>
            </div>

            @if($payments->isEmpty())
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-slate-800 text-slate-400 mb-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-white">No payment transactions</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                        Once your tenants pay rent online or you record manual payments, the ledger will populate here automatically.
                    </p>
                </div>
            @else
                <!-- Search Bar -->
                <div class="p-6 border-b border-slate-800 bg-slate-950/20 relative">
                    <div class="absolute inset-y-0 left-6 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Search ledger by reference, tenant name, email, property, unit, method, status..." 
                        class="block w-full max-w-md pl-10 pr-4 py-2.5 rounded-xl border border-slate-800 bg-slate-950 text-sm placeholder-slate-500 text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm outline-none"
                    />
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-800 text-left">
                        <thead class="bg-slate-900/60 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Transaction Code</th>
                                <th class="px-6 py-4">Tenant</th>
                                <th class="px-6 py-4">Property & Unit</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Method</th>
                                <th class="px-6 py-4 text-right">Invoice</th>
                            </tr>
                        </thead>
                        <tbody x-ref="tableBody" class="divide-y divide-slate-800 text-sm">
                            @foreach($payments as $payment)
                                <tr data-search="{{ $payment->reference_code }} {{ $payment->notes }} {{ $payment->lease->tenant->name }} {{ $payment->lease->tenant->email }} {{ $payment->lease->unit->property->name }} Unit {{ $payment->lease->unit->unit_number }} {{ $payment->payment_method }} {{ $payment->status }}" class="hover:bg-slate-800/20 transition-all duration-150">
                                    <!-- Transaction Code -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-white">{{ $payment->reference_code }}</div>
                                        @if($payment->notes)
                                            <div class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ $payment->notes }}</div>
                                        @endif
                                    </td>
                                    <!-- Tenant -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-8 w-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold text-xs">
                                                {{ substr($payment->lease->tenant->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="text-slate-200 font-semibold">{{ $payment->lease->tenant->name }}</div>
                                                <div class="text-xs text-slate-400">{{ $payment->lease->tenant->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Property & Unit -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-slate-200 font-semibold">{{ $payment->lease->unit->property->name }}</div>
                                        <div class="text-xs text-indigo-400">Unit {{ $payment->lease->unit->unit_number }}</div>
                                    </td>
                                    <!-- Amount -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-emerald-400 font-bold text-base">
                                            +₦{{ number_format($payment->amount, 2) }}
                                        </div>
                                    </td>
                                    <!-- Date -->
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-300">
                                        {{ $payment->payment_date->format('M d, Y \a\t g:i A') }}
                                    </td>
                                    <!-- Method -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-1">
                                            <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                            </span>
                                            @if($payment->status === 'pending')
                                                <span class="inline-flex items-center w-fit px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    Awaiting Verification
                                                </span>
                                            @else
                                                <span class="inline-flex items-center w-fit px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    Verified
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <!-- Receipt Invoice -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                        @if($payment->status === 'pending')
                                            <form action="{{ route('landlord.payments.verify', $payment->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button 
                                                    type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-lg transition-all cursor-pointer shadow-[0_0_15px_rgba(245,158,11,0.2)]"
                                                >
                                                    ✓ Verify
                                                </button>
                                            </form>
                                        @else
                                            <a 
                                                href="{{ route('tenant.payments.receipt', $payment->reference_code) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-3 py-1.5 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white rounded-lg transition-all"
                                            >
                                                <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                                View Receipt
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Empty Search Results State -->
                <div x-show="totalMatching === 0" class="p-16 text-center" style="display: none;" x-cloak>
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-slate-800 text-slate-405 text-slate-450 mb-4 select-none">
                        🔍
                    </div>
                    <p class="text-sm font-semibold text-slate-400">No transactions match search criteria.</p>
                </div>

                <!-- Pagination Footer -->
                <div x-show="totalPages > 1" class="px-6 py-4 bg-slate-950/20 border-t border-slate-800 flex items-center justify-between select-none" style="display: none;" x-cloak>
                    <div class="text-xs text-slate-450">
                        Showing page <span class="font-bold text-slate-300" x-text="page"></span> of <span class="font-bold text-slate-300" x-text="totalPages"></span> (<span x-text="totalMatching"></span> matching transactions)
                    </div>
                    <div class="flex items-center space-x-2">
                        <button 
                            type="button"
                            @click="if (page > 1) { page-- }" 
                            :disabled="page === 1"
                            class="px-3.5 py-2 rounded-xl border border-slate-850 bg-slate-950 text-xs font-bold text-slate-350 hover:bg-slate-900 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all"
                        >
                            Previous
                        </button>
                        <button 
                            type="button"
                            @click="if (page < totalPages) { page++ }" 
                            :disabled="page === totalPages"
                            class="px-3.5 py-2 rounded-xl border border-slate-850 bg-slate-950 text-xs font-bold text-slate-350 hover:bg-slate-900 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all"
                        >
                            Next
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Alpine.js Record Manual Payment Modal -->
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
            x-show="openRecordModal"
            x-transition
            style="display: none;"
        >
            <div 
                class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg shadow-[0_0_50px_rgba(0,0,0,0.8)] overflow-hidden"
                @click.away="openRecordModal = false"
            >
                <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white font-outfit">Record Manual Payment</h3>
                    <button 
                        @click="openRecordModal = false" 
                        class="text-slate-400 hover:text-white transition-colors"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('landlord.payments.manual') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Select Tenant / Lease -->
                    <div class="space-y-1.5">
                        <label for="lease_id" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tenant Agreement</label>
                        <select 
                            name="lease_id" 
                            id="lease_id" 
                            required
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        >
                            <option value="">-- Choose Tenant --</option>
                            @foreach($activeLeases as $lease)
                                <option value="{{ $lease->id }}">
                                    {{ $lease->tenant->name }} - {{ $lease->unit->property->name }} (Unit {{ $lease->unit->unit_number }}) - Rent: ₦{{ number_format($lease->monthly_rent, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Amount -->
                    <div class="space-y-1.5">
                        <label for="amount" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Amount Collected (₦)</label>
                        <input 
                            type="number" 
                            name="amount" 
                            id="amount" 
                            step="0.01" 
                            min="0.01"
                            required
                            placeholder="e.g. 1500.00"
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        />
                    </div>

                    <!-- Method & Date Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="payment_method" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Payment Method</label>
                            <select 
                                name="payment_method" 
                                id="payment_method" 
                                required
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                            >
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label for="payment_date" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Payment Date</label>
                            <input 
                                type="date" 
                                name="payment_date" 
                                id="payment_date" 
                                required
                                value="{{ date('Y-m-d') }}"
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                            />
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-1.5">
                        <label for="notes" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Notes / Reference Details</label>
                        <textarea 
                            name="notes" 
                            id="notes" 
                            rows="3"
                            placeholder="e.g. Check number, bank receipt reference, etc."
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        ></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 flex items-center justify-end space-x-3">
                        <button 
                            type="button" 
                            @click="openRecordModal = false"
                            class="px-5 py-3 rounded-xl border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-[0_0_20px_rgba(99,102,241,0.3)] transition-all cursor-pointer"
                        >
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alpine.js Update Bank Details Modal -->
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
            x-show="openBankModal"
            x-transition
            style="display: none;"
        >
            <div 
                class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg shadow-[0_0_50px_rgba(0,0,0,0.8)] overflow-hidden"
                @click.away="openBankModal = false"
            >
                <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white font-outfit">Update Bank Details</h3>
                    <button 
                        @click="openBankModal = false" 
                        class="text-slate-400 hover:text-white transition-colors"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('landlord.payments.bank-details') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="bank_details" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bank Transfer Instructions</label>
                        <p class="text-[10px] text-slate-500 mb-2">Input your Bank Name, Account Name, Account Number, and any instructions for your tenants.</p>
                        <textarea 
                            name="bank_details" 
                            id="bank_details" 
                            rows="6"
                            placeholder="e.g.&#10;Bank: Zenith Bank&#10;Account Number: 1022334455&#10;Account Name: Lara Landlord&#10;Instructions: Please include your Unit Number in the payment reference."
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono text-sm"
                        >{{ Auth::user()->bank_details }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 flex items-center justify-end space-x-3">
                        <button 
                            type="button" 
                            @click="openBankModal = false"
                            class="px-5 py-3 rounded-xl border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-[0_0_20px_rgba(99,102,241,0.3)] transition-all cursor-pointer"
                        >
                            Save Account Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-landlord-layout>
