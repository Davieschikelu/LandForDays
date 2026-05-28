<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LandForDays - Professional Property Management</title>

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
                background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.15) 0%, rgba(0, 0, 0, 0) 50%);
            }
        </style>
    </head>
    <body class="h-full antialiased text-slate-100 flex flex-col justify-between hero-glow bg-slate-950">
        <!-- Navigation Header -->
        <header class="w-full max-w-7xl mx-auto px-6 h-20 flex items-center justify-between border-b border-slate-900">
            <div class="flex items-center space-x-2">
                <div class="p-2 bg-indigo-650 bg-indigo-600 rounded-lg text-white font-bold text-md shadow-lg shadow-indigo-500/20">
                    LD
                </div>
                <span class="text-xl font-bold text-white tracking-tight">Land<span class="text-indigo-400">ForDays</span></span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-800 text-sm font-semibold rounded-xl text-slate-350 hover:text-white hover:bg-slate-900 transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-350 hover:text-white transition-all">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4.5 py-2 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/10 transition-all">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col items-center justify-center max-w-6xl w-full mx-auto px-6 py-20 text-center">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                🚀 Unified Property Ecosystem
            </span>
            
            <h1 class="mt-6 text-5xl md:text-7xl font-extrabold tracking-tight text-white leading-none max-w-4xl">
                Manage your properties, tenants & leases in <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">one premium space</span>.
            </h1>
            
            <p class="mt-6 text-base md:text-lg text-slate-400 max-w-2xl">
                A state-of-the-art management system providing landlords with rich analytics and unit trackers, and tenants with seamless payment and maintenance tools.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all duration-200">
                        Go to Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all duration-200">
                        Start managing for free
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold rounded-2xl text-slate-350 hover:text-white border border-slate-800 hover:bg-slate-900/50 transition-all duration-200">
                        Log in to your portal
                    </a>
                @endif
            </div>

            <!-- Features Preview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 w-full mt-24">
                <div class="p-6 bg-slate-900/40 border border-slate-900 rounded-3xl text-left hover:border-slate-800 transition-all">
                    <h3 class="text-lg font-bold text-white">Properties & Units</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                        Track vacancies, structure custom units, and view rich status logs instantly.
                    </p>
                </div>
                <div class="p-6 bg-slate-900/40 border border-slate-900 rounded-3xl text-left hover:border-slate-800 transition-all">
                    <h3 class="text-lg font-bold text-white">Tenant Invites</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                        Send digital invitations, verify profiles, and secure your tenant relations in seconds.
                    </p>
                </div>
                <div class="p-6 bg-slate-900/40 border border-slate-900 rounded-3xl text-left hover:border-slate-800 transition-all">
                    <h3 class="text-lg font-bold text-white">Direct Rent Payments</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                        Allow online card or bank rent payments with fully detailed invoicing.
                    </p>
                </div>
                <div class="p-6 bg-slate-900/40 border border-slate-900 rounded-3xl text-left hover:border-slate-800 transition-all">
                    <h3 class="text-lg font-bold text-white">Maintenance Tickets</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                        Handle repair requests, track issues in real-time, and preserve digital audit trails.
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-slate-900 bg-slate-950/50 backdrop-blur-md mt-auto">
            <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row items-center justify-between gap-8">
                <!-- Left: Brand & Copyright -->
                <div class="flex flex-col items-center md:items-start space-y-3">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-indigo-600 rounded-md text-white font-bold text-xs shadow-lg shadow-indigo-500/20">
                            LD
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">Land<span class="text-indigo-400">ForDays</span></span>
                    </div>
                    <p class="text-xs text-slate-500 text-center md:text-left">
                        &copy; {{ date('Y') }} Odoemenam David. All rights reserved.
                    </p>
                </div>

                <!-- Right: Contact Info -->
                <div class="flex flex-col sm:flex-row items-center gap-6 sm:gap-10 text-sm">
                    <!-- Email Contact -->
                    <a href="mailto:odoemenamdavid7@gmail.com" class="group flex items-center space-x-3 text-slate-450 hover:text-indigo-400 transition-all duration-200">
                        <div class="p-2.5 bg-slate-900/60 border border-slate-800 rounded-xl group-hover:border-indigo-500/30 group-hover:bg-indigo-500/10 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400 group-hover:text-indigo-400 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Email Us</p>
                            <p class="text-xs font-semibold text-slate-300 group-hover:text-white transition-colors">odoemenamdavid7@gmail.com</p>
                        </div>
                    </a>

                    <!-- Phone Contact -->
                    <a href="tel:09168211770" class="group flex items-center space-x-3 text-slate-450 hover:text-indigo-400 transition-all duration-200">
                        <div class="p-2.5 bg-slate-900/60 border border-slate-800 rounded-xl group-hover:border-indigo-500/30 group-hover:bg-indigo-500/10 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400 group-hover:text-indigo-400 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a20.373 20.373 0 0 1-6.718-6.718c-.144-.415.022-.904.387-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Call Us</p>
                            <p class="text-xs font-semibold text-slate-300 group-hover:text-white transition-colors">09168211770</p>
                        </div>
                    </a>
                </div>
            </div>
        </footer>
    </body>
</html>
