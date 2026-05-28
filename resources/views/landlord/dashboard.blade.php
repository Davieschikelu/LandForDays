<x-landlord-layout>
    <x-slot:title>Landlord Dashboard - LandForDays</x-slot:title>

    <!-- Welcome Section -->
    <div class="mb-8 flex flex-col gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Welcome back, <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">{{ Auth::user()->name }}</span>!
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Here's a premium overview of your properties, occupancy rates, and status.
            </p>
        </div>
        <div>
            <a href="{{ route('landlord.properties.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition-all duration-200">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Manage Properties
            </a>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-8">
        <!-- Properties count -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Properties</p>
                    <h3 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $propertiesCount }}</h3>
                </div>
                <div class="p-2 sm:p-4 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl text-indigo-600 dark:text-indigo-400 flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs font-semibold text-indigo-600 dark:text-indigo-400 truncate">
                <span>View properties &rarr;</span>
            </div>
        </div>

        <!-- Total Units count -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Total Units</p>
                    <h3 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $unitsCount }}</h3>
                </div>
                <div class="p-2 sm:p-4 bg-violet-50 dark:bg-violet-950/50 rounded-xl text-violet-600 dark:text-violet-400 flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs font-semibold text-violet-600 dark:text-violet-400 truncate">
                <span>Across all active properties</span>
            </div>
        </div>

        <!-- Occupied Units count -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Occupied</p>
                    <h3 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $occupiedUnitsCount }}</h3>
                </div>
                <div class="p-2 sm:p-4 bg-emerald-50 dark:bg-emerald-950/50 rounded-xl text-emerald-600 dark:text-emerald-400 flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $unitsCount > 0 ? ($occupiedUnitsCount / $unitsCount) * 100 : 0 }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] font-semibold text-slate-500 mt-1">
                    <span>Occupancy</span>
                    <span>{{ $unitsCount > 0 ? round(($occupiedUnitsCount / $unitsCount) * 100) : 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- Vacant Units count -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Vacant</p>
                    <h3 class="mt-1 sm:mt-2 text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $vacantUnitsCount }}</h3>
                </div>
                <div class="p-2 sm:p-4 bg-amber-50 dark:bg-amber-950/50 rounded-xl text-amber-600 dark:text-amber-400 flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs font-semibold text-amber-600 dark:text-amber-400 truncate">
                <span>{{ $maintenanceUnitsCount }} in maintenance</span>
            </div>
        </div>
    </div>

    <!-- Recent Actions and Properties Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Properties -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Recently Added Properties</h3>
                <a href="{{ route('landlord.properties.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700">View All</a>
            </div>

            @if ($recentProperties->isEmpty())
                <div class="p-8 text-center bg-slate-50 dark:bg-slate-950 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                    <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">No properties created yet.</p>
                    <div class="mt-4">
                        <a href="{{ route('landlord.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700">
                            Add first property
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($recentProperties as $property)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl hover:bg-slate-100/50 dark:hover:bg-slate-950 transition-colors border border-slate-100 dark:border-slate-900 min-w-0 gap-3">
                            <div class="flex items-center space-x-4 min-w-0">
                                <div class="p-3 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex-shrink-0">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 dark:text-white truncate">{{ $property->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate capitalize">{{ $property->type }} &bull; {{ $property->address }}, {{ $property->city }}</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('landlord.properties.show', $property->id) }}" class="inline-flex items-center justify-center p-2 bg-indigo-600/10 text-indigo-600 dark:text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Activity / Overview panel -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Local Operations</h3>
                <div class="space-y-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm font-bold">1</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Phase 1 Complete</h4>
                            <p class="text-xs text-slate-500">Foundation, authentication and role management are set up.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-violet-500/10 text-violet-600 dark:text-violet-400 flex items-center justify-center text-sm font-bold">2</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Phase 2 In-Progress</h4>
                            <p class="text-xs text-slate-500">Property & unit database setup done. Ready to manage properties!</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3 opacity-50">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-slate-500/10 text-slate-500 flex items-center justify-center text-sm font-bold">3</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Phase 3 Up next</h4>
                            <p class="text-xs text-slate-500">Tenant invites & onboard registration links generation.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-2xs text-slate-400">Environment: Local XAMPP &bull; Connected</p>
            </div>
        </div>
    </div>
</x-landlord-layout>
