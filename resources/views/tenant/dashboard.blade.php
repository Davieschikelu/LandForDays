<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Tenant Dashboard - LandForDays</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
            .hero-glow {
                background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.12) 0%, rgba(0, 0, 0, 0) 50%);
            }
        </style>

        <!-- Dark mode init — must run before paint to prevent flash -->
        <script>
            (function() {
                const saved = localStorage.getItem('lfd-theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'light') {
                    document.documentElement.classList.remove('dark');
                } else {
                    // Default tenant dashboard to dark (it was originally dark-only)
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
    </head>
    <body class="h-full antialiased flex flex-col justify-between hero-glow bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100" x-data="{ openPayModal: false }">
        
        <!-- Header Nav -->
        <header class="w-full border-b border-slate-200 dark:border-slate-900 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-2 min-w-0">
                    <div class="p-2 bg-indigo-600 rounded-lg text-white font-bold text-md shadow-lg shadow-indigo-500/20 flex-shrink-0">
                        LD
                    </div>
                    <span class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight truncate">Land<span class="text-indigo-600 dark:text-indigo-400">ForDays</span></span>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-indigo-600 dark:text-indigo-400 capitalize tracking-wider">{{ Auth::user()->role }}</span>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button
                        onclick="toggleTheme()"
                        class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        title="Toggle dark/light mode"
                    >
                        <!-- Sun icon (shown in dark mode) -->
                        <svg class="h-5 w-5 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg class="h-5 w-5 block dark:hidden text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 dark:border-slate-900 text-xs font-bold rounded-xl text-rose-500 dark:text-rose-400 hover:text-white hover:bg-rose-500/10 hover:border-rose-500/25 transition-all cursor-pointer">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-8 md:py-12 space-y-10">
            
            @if (session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center space-x-3 shadow-md animate-pulse">
                    <svg class="h-5 w-5 text-emerald-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if($lease)
                @if(!$lease->is_confirmed)
                    <!-- Beautiful Restricted Tenancy Activation View -->
                    <div class="space-y-8 max-w-3xl mx-auto py-8">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                🔒 Awaiting Tenancy Activation
                            </span>
                            <h1 class="mt-3 text-4xl font-bold text-slate-900 dark:text-white sm:text-5xl leading-none">
                                Activate Your Tenancy at <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-emerald-400 bg-clip-text text-transparent">{{ $lease->unit->property->name }}</span>
                            </h1>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Unit {{ $lease->unit->unit_number }} &bull; {{ $lease->unit->property->address }}, {{ $lease->unit->property->city }}
                            </p>
                        </div>

                        <!-- Progress / Setup Cards -->
                        <div class="bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-900 rounded-3xl p-6 sm:p-8 space-y-8">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Onboarding Checklist</h3>
                            
                            <div class="space-y-6">
                                <!-- Step 1: Download Template -->
                                <div class="flex items-start space-x-4">
                                    <div class="h-8 w-8 rounded-full {{ $lease->agreement_path ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-indigo-500/10 text-indigo-400' }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        1
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Download Tenancy Agreement Template</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            Please download the tenancy agreement form prepared by your landlord. Read it carefully and sign the document.
                                        </p>
                                        <div class="pt-2">
                                            @if($lease->agreement_path)
                                                <a href="{{ route('agreements.download', ['type' => 'template', 'id' => $lease->id]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 transition-all cursor-pointer shadow-md">
                                                    📥 Download Agreement Form
                                                </a>
                                            @else
                                                <span class="inline-flex items-center text-xs font-semibold text-amber-500 bg-amber-500/10 px-3 py-1 rounded-lg border border-amber-500/20">
                                                    ⚠️ Your landlord hasn't uploaded a template yet. Please contact them.
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Upload Signed Copy -->
                                <div class="flex items-start space-x-4 border-t border-slate-200 dark:border-slate-800 pt-6">
                                    <div class="h-8 w-8 rounded-full {{ $lease->signed_agreement_path ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-indigo-500/10 text-indigo-400' }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        2
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Upload Signed Agreement Copy</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            Upload a scanned PDF or photo copy of the fully signed tenancy agreement document.
                                        </p>
                                        
                                        <form action="{{ route('tenant.leases.upload-agreement', $lease->id) }}" method="POST" enctype="multipart/form-data" class="pt-3 space-y-3">
                                            @csrf
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                <input 
                                                    type="file" 
                                                    name="signed_agreement" 
                                                    required 
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="block w-full max-w-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3"
                                                >
                                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-xs font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-500 transition-all cursor-pointer shadow-md w-fit">
                                                    📤 Upload Signed Document
                                                </button>
                                            </div>
                                        </form>

                                        @if($lease->signed_agreement_path)
                                            <div class="pt-2">
                                                <a href="{{ route('agreements.download', ['type' => 'signed', 'id' => $lease->id]) }}" class="inline-flex items-center text-xs font-semibold text-indigo-500 hover:text-indigo-650 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline">
                                                    🔍 View your uploaded signed copy
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Step 3: Landlord Confirmation -->
                                <div class="flex items-start space-x-4 border-t border-slate-200 dark:border-slate-800 pt-6">
                                    <div class="h-8 w-8 rounded-full {{ $lease->signed_agreement_path ? 'bg-amber-500/10 text-amber-400' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-650' }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        3
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Awaiting Confirmation</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            Once you upload the signed agreement, your landlord will verify it and unlock all dashboard features (paying rent, checking receipts, maintenance tickets).
                                        </p>
                                        <div class="pt-3">
                                            @if($lease->signed_agreement_path)
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 animate-pulse">
                                                    ⏳ Pending Landlord Review & Activation
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-500 dark:text-slate-450 border border-slate-200 dark:border-slate-800">
                                                    Awaiting Signed Agreement Upload
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                <!-- Active Lease Dashboard -->
                <div class="space-y-8">
                    <!-- Heading -->
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            🟢 Active Lease Signed
                        </span>
                        <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl leading-none">
                            Welcome to <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-emerald-400 bg-clip-text text-transparent">{{ $lease->unit->property->name }}</span>
                        </h1>
                        <p class="mt-2 text-sm text-slate-400">
                            Unit {{ $lease->unit->unit_number }} &bull; {{ $lease->unit->property->address }}, {{ $lease->unit->property->city }}
                        </p>
                    </div>

                    <!-- Layout: Split Cards -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Lease Overview card (Left) -->
                        <div class="lg:col-span-2 bg-slate-900/40 border border-slate-900 p-8 rounded-3xl space-y-6">
                            <h3 class="text-xl font-bold text-white tracking-tight">Lease Agreement</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Monthly Rent</span>
                                    <span class="text-2xl font-extrabold text-white mt-1 block">₦{{ number_format($lease->monthly_rent, 2) }}</span>
                                    <span class="text-[10px] text-slate-450 mt-0.5 block">Due on the 1st of every month</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Lease Term</span>
                                    <span class="text-base font-bold text-slate-200 mt-1 block">
                                        {{ $lease->start_date->format('M d, Y') }} &rarr; {{ $lease->end_date->format('M d, Y') }}
                                    </span>
                                    <span class="text-[10px] text-slate-450 mt-0.5 block">Standard fixed term tenancy</span>
                                </div>
                            </div>

                            <div class="border-t border-slate-800/80 pt-6">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Payment Status</span>
                                <div class="mt-3 flex flex-col bg-slate-950/50 p-4 rounded-2xl border border-slate-800 gap-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl font-bold text-sm flex-shrink-0">
                                            ✓
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-white">Rent Account</p>
                                            <p class="text-[10px] text-slate-450">
                                                @if($payments->isNotEmpty())
                                                    Last payment processed successfully.
                                                @else
                                                    No payments recorded yet for this term.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <button 
                                            @click="openPayModal = true"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 transition-all cursor-pointer shadow-[0_0_15px_rgba(99,102,241,0.2)]"
                                        >
                                            💳 Pay Rent Online
                                        </button>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Current
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance & Quick Actions (Right) -->
                        <div class="bg-slate-900/40 border border-slate-900 p-8 rounded-3xl flex flex-col justify-between space-y-6">
                            <div>
                                <h3 class="text-xl font-bold text-white tracking-tight">Support Tickets</h3>
                                <p class="text-xs text-slate-450 mt-1">
                                    Need assistance? Report issues or request repairs directly to your landlord workspace.
                                </p>
                                
                                <div class="mt-6 bg-emerald-500/5 border border-emerald-500/10 p-4 rounded-2xl text-center">
                                    <span class="text-2xl block">🛠</span>
                                    <h4 class="text-xs font-bold text-emerald-400 mt-2">Maintenance Active</h4>
                                    <p class="text-[10px] text-slate-450 mt-1 leading-relaxed">
                                        You can now lodge issue tickets, upload photos, and track repair logs in real-time.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('tenant.maintenance.index') }}" class="w-full inline-flex items-center justify-center px-5 py-3 border border-indigo-500/30 hover:border-indigo-500 hover:bg-indigo-600/10 text-xs font-bold rounded-2xl text-indigo-400 hover:text-white transition-all shadow-[0_0_15px_rgba(99,102,241,0.05)] cursor-pointer">
                                Request Assistance
                            </a>
                        </div>
                    </div>

                    <!-- Billing & Payment History -->
                    <div class="bg-slate-900/20 border border-slate-900 rounded-3xl overflow-hidden mt-12">
                        <div class="px-6 py-5 border-b border-slate-900 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white">Billing & Payments History</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-900 text-indigo-400 border border-slate-800 rounded-full">
                                {{ $payments->count() }} transactions
                            </span>
                        </div>

                        @if($payments->isEmpty())
                            <div class="p-8 text-center">
                                <p class="text-xs text-slate-550">No historic payments logged yet.</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-900">
                                @foreach($payments as $payment)
                                    <div class="p-4 sm:p-6 flex flex-col gap-3 hover:bg-slate-900/30 transition-all duration-150">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="text-xs font-bold text-white">{{ $payment->reference_code }}</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-indigo-500/10 text-indigo-400">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                </span>
                                                @if($payment->status === 'pending')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                        Pending Verification
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                        Verified
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-slate-450 mt-0.5">
                                                Initiated on {{ $payment->payment_date->format('F d, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-base font-extrabold text-emerald-400">+₦{{ number_format($payment->amount, 2) }}</span>
                                            @if($payment->status === 'pending')
                                                <span class="text-[10px] text-amber-400 font-bold italic tracking-wide">Awaiting approval</span>
                                            @else
                                                <a 
                                                    href="{{ route('tenant.payments.receipt', $payment->reference_code) }}"
                                                    target="_blank"
                                                    class="px-3 py-1.5 border border-slate-800 hover:border-slate-700 text-[10px] font-bold rounded-xl text-slate-350 hover:text-white transition-all"
                                                >
                                                    View Receipt
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Form: INLINE on mobile/tablet, FIXED MODAL on desktop -->

                {{-- ── MOBILE / TABLET: inline panel (no fixed overlay) ── --}}
                <div 
                    x-show="openPayModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4"
                    x-data="{ activeMethod: 'card' }"
                    class="lg:hidden bg-slate-900 border border-slate-800 rounded-3xl shadow-xl overflow-hidden mt-6"
                    style="display: none;"
                >
                    <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white font-outfit">Settle Rent</h3>
                            <p class="text-[10px] text-slate-400 mt-0.5">Choose a payment method below</p>
                        </div>
                        <button 
                            @click="openPayModal = false" 
                            class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-colors cursor-pointer"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('tenant.payments.checkout') }}" method="POST" class="p-5 space-y-5">
                        @csrf
                        <input type="hidden" name="lease_id" value="{{ $lease->id }}"/>
                        <input type="hidden" name="payment_method" :value="activeMethod"/>

                        <!-- Invoice Amount Summary -->
                        <div class="bg-indigo-600/10 border border-indigo-500/20 p-4 rounded-2xl flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Amount Due</p>
                                <p class="text-xs text-slate-300 mt-0.5">Unit {{ $lease->unit->unit_number }} Monthly Rent</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-extrabold text-white font-outfit">₦{{ number_format($lease->monthly_rent, 2) }}</span>
                                <input type="hidden" name="amount" value="{{ $lease->monthly_rent }}"/>
                            </div>
                        </div>

                        <!-- Payment Method Tabs (2x2 grid) -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Choose Payment Method</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="activeMethod = 'card'"
                                    :class="activeMethod === 'card' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                    class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                    <span>💳</span><span>Online</span>
                                </button>
                                <button type="button" @click="activeMethod = 'bank_transfer'"
                                    :class="activeMethod === 'bank_transfer' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                    class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                    <span>🏛️</span><span>Bank Transfer</span>
                                </button>
                                <button type="button" @click="activeMethod = 'cash'"
                                    :class="activeMethod === 'cash' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                    class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                    <span>💵</span><span>Cash</span>
                                </button>
                                <button type="button" @click="activeMethod = 'check'"
                                    :class="activeMethod === 'check' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                    class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                    <span>✍️</span><span>Cheque</span>
                                </button>
                            </div>
                        </div>

                        <!-- Card Section -->
                        <div x-show="activeMethod === 'card'" class="space-y-4" x-transition>
                            <div class="space-y-1.5">
                                <label for="card_name_m" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cardholder Name</label>
                                <input type="text" name="card_name" id="card_name_m" placeholder="e.g. Theresa Tenant"
                                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                            </div>
                            <div class="space-y-1.5">
                                <label for="card_number_m" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Card Number</label>
                                <div class="relative">
                                    <input type="text" name="card_number" id="card_number_m" maxlength="19" placeholder="4111 2222 3333 4444"
                                        class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-4 pr-10 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                                    <span class="absolute right-3.5 top-3.5 text-slate-500 text-sm">💳</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1.5">
                                    <label for="card_expiry_m" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Expiry</label>
                                    <input type="text" name="card_expiry" id="card_expiry_m" placeholder="12/2028" maxlength="7"
                                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="card_cvv_m" class="text-xs font-bold text-slate-400 uppercase tracking-wider">CVV</label>
                                    <input type="password" name="card_cvv" id="card_cvv_m" maxlength="4" placeholder="***"
                                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                                </div>
                            </div>
                            <div class="text-[10px] text-slate-500 flex items-center space-x-1.5">
                                <span>🔒</span><span>Secured checkout. All payments require landlord verification.</span>
                            </div>
                        </div>

                        <!-- Bank Transfer Section -->
                        <div x-show="activeMethod === 'bank_transfer'" class="space-y-3" x-transition style="display: none;">
                            <div class="bg-indigo-950/40 border border-indigo-900/40 p-4 rounded-2xl space-y-3">
                                <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Landlord Bank Instructions</h4>
                                @if($lease->unit->property->landlord->bank_details)
                                    <div class="text-slate-200 text-xs leading-relaxed whitespace-pre-line font-mono bg-slate-950 p-3 rounded-xl border border-slate-800 select-all">{{ $lease->unit->property->landlord->bank_details }}</div>
                                    <p class="text-[10px] text-slate-450 leading-relaxed">Transfer <strong class="text-white">₦{{ number_format($lease->monthly_rent, 2) }}</strong> to the account above, then click Confirm.</p>
                                @else
                                    <div class="text-slate-400 text-xs italic bg-slate-950 p-4 rounded-xl border border-slate-800 text-center">⚠️ Landlord has not configured their bank details yet.</div>
                                @endif
                            </div>
                        </div>

                        <!-- Cash Section -->
                        <div x-show="activeMethod === 'cash'" class="space-y-3" x-transition style="display: none;">
                            <div class="bg-slate-950 border border-slate-800 p-4 rounded-2xl text-center space-y-2">
                                <span class="text-2xl block">💵</span>
                                <h4 class="text-xs font-bold text-white uppercase tracking-wider">Direct Cash Settlement</h4>
                                <p class="text-[10px] text-slate-400 leading-relaxed">Submit this request to alert your landlord, then present cash to complete verification.</p>
                            </div>
                        </div>

                        <!-- Cheque Section -->
                        <div x-show="activeMethod === 'check'" class="space-y-3" x-transition style="display: none;">
                            <div class="bg-slate-950 border border-slate-800 p-4 rounded-2xl text-center space-y-2">
                                <span class="text-2xl block">✍️</span>
                                <h4 class="text-xs font-bold text-white uppercase tracking-wider">Cheque Deposit</h4>
                                <p class="text-[10px] text-slate-400 leading-relaxed">Submit this request, then present the physical cheque to your landlord for verification.</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-2 flex items-center justify-between gap-3">
                            <button type="button" @click="openPayModal = false"
                                class="flex-1 py-3 rounded-xl border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors cursor-pointer text-sm font-semibold">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-[0_0_20px_rgba(99,102,241,0.3)] transition-all cursor-pointer text-sm">
                                <span x-text="activeMethod === 'card' ? 'Process Payment' : (activeMethod === 'bank_transfer' ? 'Confirm Transfer' : 'Confirm Settlement')"></span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── DESKTOP (lg+): fixed centered modal overlay ── --}}
                <div 
                    class="hidden lg:flex fixed inset-0 z-50 items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
                    x-show="openPayModal"
                    x-transition
                    style="display: none;"
                >
                    <div 
                        class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-[0_0_50px_rgba(0,0,0,0.8)]"
                        @click.away="openPayModal = false"
                        x-data="{ activeMethod: 'card' }"
                    >
                        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white font-outfit">Settle Rent</h3>
                            <button 
                                @click="openPayModal = false" 
                                class="text-slate-400 hover:text-white transition-colors cursor-pointer"
                            >
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Payment Checkout Form -->
                        <form action="{{ route('tenant.payments.checkout') }}" method="POST" class="p-6 space-y-5">
                            @csrf
                            <input type="hidden" name="lease_id" value="{{ $lease->id }}"/>
                            <input type="hidden" name="payment_method" :value="activeMethod"/>

                            <!-- Invoice Amount Summary -->
                            <div class="bg-indigo-600/10 border border-indigo-500/20 p-4 rounded-2xl flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Payment Amount</p>
                                    <p class="text-xs text-slate-300 mt-0.5">Unit {{ $lease->unit->unit_number }} Monthly Rent</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-extrabold text-white font-outfit">₦{{ number_format($lease->monthly_rent, 2) }}</span>
                                    <input type="hidden" name="amount" value="{{ $lease->monthly_rent }}"/>
                                </div>
                            </div>

                            <!-- Payment Method Tabs -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Choose Payment Method</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="activeMethod = 'card'"
                                        :class="activeMethod === 'card' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                        class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                        <span>💳</span><span>Online Payment</span>
                                    </button>
                                    <button type="button" @click="activeMethod = 'bank_transfer'"
                                        :class="activeMethod === 'bank_transfer' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                        class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                        <span>🏛️</span><span>Bank Transfer</span>
                                    </button>
                                    <button type="button" @click="activeMethod = 'cash'"
                                        :class="activeMethod === 'cash' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                        class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                        <span>💵</span><span>Cash</span>
                                    </button>
                                    <button type="button" @click="activeMethod = 'check'"
                                        :class="activeMethod === 'check' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-800 bg-slate-950 text-slate-400'"
                                        class="flex items-center space-x-2 p-3 border rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                        <span>✍️</span><span>Cheque</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Card Section -->
                            <div x-show="activeMethod === 'card'" class="space-y-4" x-transition>
                                <div class="space-y-1.5">
                                    <label for="card_name" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cardholder Name</label>
                                    <input type="text" name="card_name" id="card_name" placeholder="e.g. Theresa Tenant"
                                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="card_number" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Card Number</label>
                                    <div class="relative">
                                        <input type="text" name="card_number" id="card_number" maxlength="19" placeholder="4111 2222 3333 4444"
                                            class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-4 pr-10 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                                        <span class="absolute right-3.5 top-3.5 text-slate-500 text-sm">💳</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label for="card_expiry" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Expiry (MM/YYYY)</label>
                                        <input type="text" name="card_expiry" id="card_expiry" placeholder="12/2028" maxlength="7"
                                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="card_cvv" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Security CVV</label>
                                        <input type="password" name="card_cvv" id="card_cvv" maxlength="4" placeholder="***"
                                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"/>
                                    </div>
                                </div>
                                <div class="text-[10px] text-slate-500 flex items-center space-x-1.5 pt-1">
                                    <span>🔒</span>
                                    <span>Secured checkout. All payments require landlord verification.</span>
                                </div>
                            </div>

                            <!-- Bank Transfer Section -->
                            <div x-show="activeMethod === 'bank_transfer'" class="space-y-3" x-transition style="display: none;">
                                <div class="bg-indigo-950/40 border border-indigo-900/40 p-4 rounded-2xl space-y-3">
                                    <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Landlord Bank Instructions</h4>
                                    @if($lease->unit->property->landlord->bank_details)
                                        <div class="text-slate-200 text-xs leading-relaxed whitespace-pre-line font-mono bg-slate-950 p-4 rounded-xl border border-slate-800 select-all">
                                            {{ $lease->unit->property->landlord->bank_details }}
                                        </div>
                                        <p class="text-[10px] text-slate-450 leading-relaxed mt-2">
                                            Please make the transfer of <strong class="text-white">₦{{ number_format($lease->monthly_rent, 2) }}</strong> to the account above. Once completed, click "Confirm Bank Transfer" to submit your request for landlord verification.
                                        </p>
                                    @else
                                        <div class="text-slate-400 text-xs italic bg-slate-950 p-4 rounded-xl border border-slate-850 text-center">
                                            ⚠️ Landlord has not configured their bank details yet. Please contact them or select a different method.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Cash Section -->
                            <div x-show="activeMethod === 'cash'" class="space-y-3" x-transition style="display: none;">
                                <div class="bg-slate-950 border border-slate-850 p-4 rounded-2xl text-center space-y-2">
                                    <span class="text-2xl block">💵</span>
                                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Direct Cash Settlement</h4>
                                    <p class="text-[10px] text-slate-450 leading-relaxed max-w-xs mx-auto">
                                        You are paying rent in physical cash. Submit this request to alert your landlord, and present the cash to them to complete verification.
                                    </p>
                                </div>
                            </div>

                            <!-- Cheque Section -->
                            <div x-show="activeMethod === 'check'" class="space-y-3" x-transition style="display: none;">
                                <div class="bg-slate-950 border border-slate-850 p-4 rounded-2xl text-center space-y-2">
                                    <span class="text-2xl block">✍️</span>
                                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Cheque Deposit</h4>
                                    <p class="text-[10px] text-slate-450 leading-relaxed max-w-xs mx-auto">
                                        Submit this request, and present the physical paper cheque directly to your landlord for verification.
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="pt-4 flex items-center justify-end space-x-3">
                                <button type="button" @click="openPayModal = false"
                                    class="px-5 py-3 rounded-xl border border-slate-800 hover:bg-slate-800 text-slate-350 hover:text-white transition-colors cursor-pointer">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-[0_0_20px_rgba(99,102,241,0.3)] transition-all cursor-pointer">
                                    <span x-text="activeMethod === 'card' ? 'Process Payment' : (activeMethod === 'bank_transfer' ? 'Confirm Bank Transfer' : 'Confirm Settlement')"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @else

                <!-- Pending Invitation Dashboard -->
                <div class="space-y-12">
                    <div class="text-center max-w-2xl mx-auto space-y-4">
                        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                            🔍 Pending Invite Verification
                        </span>
                        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl leading-none">
                            Your home, managed <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">effortlessly</span>.
                        </h1>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Welcome to your unified portal. Once your landlord sends your property onboarding token invite, you'll be able to activate your lease, pay rent, and file maintenance reports.
                        </p>
                    </div>

                    <!-- Features overview -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-slate-900/40 p-6 border border-slate-900 rounded-3xl space-y-3">
                            <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl w-fit">
                                🔑
                            </div>
                            <h3 class="text-base font-bold text-white">Lease Verification</h3>
                            <p class="text-xs text-slate-450 leading-relaxed">
                                Secure your account using landlord-provided onboard tokens to review and confirm lease metrics.
                            </p>
                        </div>
                        <div class="bg-slate-900/40 p-6 border border-slate-900 rounded-3xl space-y-3">
                            <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl w-fit">
                                💳
                            </div>
                            <h3 class="text-base font-bold text-white">Direct Payments</h3>
                            <p class="text-xs text-slate-450 leading-relaxed">
                                Schedule recurring transactions, settle invoice balances, and store historic payment receipts.
                            </p>
                        </div>
                        <div class="bg-slate-900/40 p-6 border border-slate-900 rounded-3xl space-y-3">
                            <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl w-fit">
                                🛠
                            </div>
                            <h3 class="text-base font-bold text-white">Maintenance Requests</h3>
                            <p class="text-xs text-slate-450 leading-relaxed">
                                Lodge ticket reports with photo attachments and track workorders dynamically.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-6 h-16 border-t border-slate-900 flex items-center justify-between text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} LandForDays Inc. All rights reserved.
            </div>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-slate-350">Privacy</a>
                <a href="#" class="hover:text-slate-350">Terms</a>
            </div>
        </footer>

        <!-- Dark Mode Toggle Script -->
        <script>
            function toggleTheme() {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('lfd-theme', isDark ? 'dark' : 'light');
            }
        </script>
    </body>

</html>
