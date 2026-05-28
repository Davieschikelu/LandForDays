<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'LandLord - Property Management' }}</title>

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
            .sidebar-gradient {
                background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
            }
        </style>
    </head>
    <body class="h-full antialiased text-slate-900 dark:text-slate-50" x-data="{ mobileSidebarOpen: false }">
        <div class="min-h-full flex">
            <!-- Desktop Sidebar -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64 border-r border-slate-200 dark:border-slate-800 sidebar-gradient text-slate-300">
                    <!-- Logo / Brand -->
                    <div class="flex items-center h-16 px-6 border-b border-slate-800">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-indigo-600 rounded-lg text-white font-bold text-lg shadow-lg shadow-indigo-500/30">
                                LD
                            </div>
                            <span class="text-xl font-bold text-white tracking-tight">Land<span class="text-indigo-400">ForDays</span></span>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="flex-1 flex flex-col overflow-y-auto px-4 py-6 space-y-7">
                        <nav class="space-y-1.5">
                            <a href="{{ route('landlord.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('landlord.properties.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.properties.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Properties
                            </a>
                            
                            <a href="{{ route('landlord.tenants.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.tenants.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Tenants
                            </a>

                            <a href="{{ route('landlord.payments.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.payments.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 6h4"/>
                                </svg>
                                Payments
                            </a>

                            <a href="{{ route('landlord.maintenance.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.maintenance.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Maintenance
                            </a>
                        </nav>
                    </div>

                    <!-- User Account Card -->
                    <div class="p-4 border-t border-slate-800">
                        <div class="flex items-center space-x-3 p-3 bg-slate-900/60 rounded-xl">
                            <div class="h-10 w-10 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate capitalize">{{ Auth::user()->role }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Drawer Backdrop -->
            <div x-show="mobileSidebarOpen" class="fixed inset-0 z-40 lg:hidden" style="display: none;">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="mobileSidebarOpen = false"></div>
                <div class="fixed inset-y-0 left-0 flex flex-col w-64 sidebar-gradient text-slate-300 z-50">
                    <!-- Logo / Brand -->
                    <div class="flex items-center h-16 px-6 border-b border-slate-800 justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-indigo-600 rounded-lg text-white font-bold text-lg shadow-lg">
                                LD
                            </div>
                            <span class="text-xl font-bold text-white tracking-tight">Land<span class="text-indigo-400">ForDays</span></span>
                        </div>
                        <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex-1 flex flex-col overflow-y-auto px-4 py-6 space-y-7">
                        <nav class="space-y-1.5">
                            <a href="{{ route('landlord.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('landlord.properties.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.properties.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Properties
                            </a>
                            
                            <a href="{{ route('landlord.tenants.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.tenants.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Tenants
                            </a>

                            <a href="{{ route('landlord.payments.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.payments.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 6h4"/>
                                </svg>
                                Payments
                            </a>

                            <a href="{{ route('landlord.maintenance.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('landlord.maintenance.*') ? 'bg-indigo-600/20 text-indigo-400 border-l-4 border-indigo-500 font-semibold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-100' }}">
                                <svg class="mr-3 h-5 w-5 transition-colors group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Maintenance
                            </a>
                        </nav>
                    </div>

                    <!-- User Account Card -->
                    <div class="p-4 border-t border-slate-800">
                        <div class="flex items-center space-x-3 p-3 bg-slate-900/60 rounded-xl">
                            <div class="h-10 w-10 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate capitalize">{{ Auth::user()->role }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Body Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Mobile top header bar -->
                <div class="lg:hidden flex items-center justify-between h-16 bg-white dark:bg-slate-900 px-6 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-indigo-600 rounded-lg text-white font-bold text-md shadow">
                            LD
                        </div>
                        <span class="text-lg font-bold tracking-tight">Land<span class="text-indigo-600">ForDays</span></span>
                    </div>
                    <button @click="mobileSidebarOpen = true" class="text-slate-500 hover:text-indigo-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Main Content Scroll Container -->
                <main class="flex-1 overflow-y-auto focus:outline-none bg-slate-50 dark:bg-slate-950 p-6 lg:p-10">
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center space-x-3 shadow-sm animate-pulse">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center space-x-3 shadow-sm">
                            <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
