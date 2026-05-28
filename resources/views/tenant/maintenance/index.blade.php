<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Report Issue - LandForDays</title>

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
                background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.08) 0%, rgba(0, 0, 0, 0) 60%);
            }
        </style>
    </head>
    <body class="h-full antialiased text-slate-100 flex flex-col justify-between hero-glow bg-slate-950" x-data="{ 
        category: 'plumbing',
        priority: 'medium',
        fileName: ''
    }">
        
        <!-- Header Nav -->
        <header class="w-full border-b border-slate-900 bg-slate-950/80 backdrop-blur-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <a href="{{ route('tenant.dashboard') }}" class="flex items-center space-x-2">
                    <div class="p-2 bg-indigo-600 rounded-lg text-white font-bold text-md shadow-lg shadow-indigo-500/20">
                        LD
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">Land<span class="text-indigo-400">ForDays</span></span>
                </a>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('tenant.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white transition-colors">
                        &larr; Back to Dashboard
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 max-w-5xl w-full mx-auto px-6 py-12 md:py-16 space-y-12">
            
            <!-- Notifications -->
            @if (session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center space-x-3 shadow-md">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-450 rounded-2xl flex items-center space-x-3 shadow-md">
                    <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-450 rounded-2xl space-y-1 shadow-md">
                    @foreach ($errors->all() as $error)
                        <div class="text-xs font-semibold flex items-center space-x-2">
                            <span>•</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- LEFT COLUMN: SUBMISSION FORM -->
                <div class="lg:col-span-2 bg-slate-900/40 border border-slate-900 p-8 rounded-3xl space-y-6">
                    <div>
                        <h2 class="text-2xl font-extrabold text-white tracking-tight">Report a Maintenance Issue</h2>
                        <p class="text-xs text-slate-400 mt-1">Submit your request below. Your landlord is notified instantly and will update you directly.</p>
                    </div>

                    @if($lease)
                        <form action="{{ route('tenant.maintenance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- Hidden category input bound to Alpine click-pills -->
                            <input type="hidden" name="category" x-model="category" />
                            
                            <!-- Styled Category Grid -->
                            <div class="space-y-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Category</span>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <button type="button" @click="category = 'plumbing'" :class="category === 'plumbing' ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center transition-all cursor-pointer">
                                        <span class="text-lg">🚰</span>
                                        <span class="text-[10px] font-bold mt-1">Plumbing</span>
                                    </button>
                                    <button type="button" @click="category = 'electrical'" :class="category === 'electrical' ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center transition-all cursor-pointer">
                                        <span class="text-lg">⚡</span>
                                        <span class="text-[10px] font-bold mt-1">Electrical</span>
                                    </button>
                                    <button type="button" @click="category = 'appliance'" :class="category === 'appliance' ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center transition-all cursor-pointer">
                                        <span class="text-lg">🔌</span>
                                        <span class="text-[10px] font-bold mt-1">Appliance</span>
                                    </button>
                                    <button type="button" @click="category = 'hvac'" :class="category === 'hvac' ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center transition-all cursor-pointer">
                                        <span class="text-lg">❄️</span>
                                        <span class="text-[10px] font-bold mt-1">HVAC / Air</span>
                                    </button>
                                    <button type="button" @click="category = 'structural'" :class="category === 'structural' ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center transition-all cursor-pointer">
                                        <span class="text-lg">🧱</span>
                                        <span class="text-[10px] font-bold mt-1">Structural</span>
                                    </button>
                                    <button type="button" @click="category = 'other'" :class="category === 'other' ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center transition-all cursor-pointer">
                                        <span class="text-lg">🛠</span>
                                        <span class="text-[10px] font-bold mt-1">Other</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden priority input bound to Alpine HSL pills -->
                            <input type="hidden" name="priority" x-model="priority" />

                            <!-- Styled Priority Actions -->
                            <div class="space-y-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Priority Level</span>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <button type="button" @click="priority = 'low'" :class="priority === 'low' ? 'bg-blue-500/20 border-blue-500 text-blue-400 font-extrabold shadow-[0_0_15px_rgba(59,130,246,0.15)]' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="py-2.5 rounded-xl border text-xs font-semibold tracking-wider uppercase transition-all cursor-pointer">
                                        Low
                                    </button>
                                    <button type="button" @click="priority = 'medium'" :class="priority === 'medium' ? 'bg-yellow-500/20 border-yellow-500 text-yellow-400 font-extrabold shadow-[0_0_15px_rgba(234,179,8,0.15)]' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="py-2.5 rounded-xl border text-xs font-semibold tracking-wider uppercase transition-all cursor-pointer">
                                        Medium
                                    </button>
                                    <button type="button" @click="priority = 'high'" :class="priority === 'high' ? 'bg-orange-500/20 border-orange-500 text-orange-400 font-extrabold shadow-[0_0_15px_rgba(249,115,22,0.15)]' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="py-2.5 rounded-xl border text-xs font-semibold tracking-wider uppercase transition-all cursor-pointer">
                                        High
                                    </button>
                                    <button type="button" @click="priority = 'emergency'" :class="priority === 'emergency' ? 'bg-red-500/20 border-red-500 text-red-400 font-extrabold shadow-[0_0_15px_rgba(239,68,68,0.15)]' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'" class="py-2.5 rounded-xl border text-xs font-semibold tracking-wider uppercase transition-all cursor-pointer">
                                        Emergency
                                    </button>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-1.5">
                                <label for="description" class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Description of Issue</label>
                                <textarea 
                                    name="description" 
                                    id="description" 
                                    rows="4" 
                                    required 
                                    placeholder="Please describe the issue in detail. e.g. Kitchen tap is leaking heavily under the sink. Wood is starting to rot..."
                                    class="w-full bg-slate-950 border border-slate-800 rounded-2xl p-4 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none leading-relaxed text-sm"
                                ></textarea>
                            </div>

                            <!-- Image Upload Dropzone -->
                            <div class="space-y-1.5">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Upload Photo (Optional)</span>
                                <div class="relative border-2 border-dashed border-slate-800 rounded-2xl hover:border-slate-700 bg-slate-950 transition-colors p-6 text-center">
                                    <input 
                                        type="file" 
                                        name="photo" 
                                        id="photo" 
                                        accept="image/*"
                                        @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    />
                                    <div class="space-y-2">
                                        <span class="text-2xl block">📸</span>
                                        <p class="text-xs font-semibold text-slate-350" x-text="fileName ? fileName : 'Drag & drop your photo, or click to browse'"></p>
                                        <p class="text-[10px] text-slate-500">Supports PNG, JPG or JPEG. Max size 5MB.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-indigo-650 bg-indigo-600 hover:bg-indigo-550 text-white rounded-2xl text-xs font-bold shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/35 transition-all cursor-pointer">
                                    Submit Request
                                </button>
                            </div>

                        </form>
                    @else
                        <div class="p-8 text-center bg-slate-950 rounded-2xl border border-slate-900">
                            <span class="text-2xl">⚠️</span>
                            <h3 class="text-sm font-bold text-white mt-2">Active Lease Required</h3>
                            <p class="text-xs text-slate-450 mt-1 leading-relaxed">
                                You cannot report property issues because your landlord has not active-linked your lease yet.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- RIGHT COLUMN: ACTIVE TIMELINE & LOG -->
                <div class="space-y-8">
                    
                    <div class="bg-slate-900/20 border border-slate-900 rounded-3xl overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-900 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white">Your Request Log</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-900 text-indigo-400 border border-slate-800 rounded-full">
                                {{ $requests->count() }} total
                            </span>
                        </div>

                        @if($requests->isEmpty())
                            <div class="p-8 text-center">
                                <p class="text-xs text-slate-500">You have not submitted any maintenance requests yet.</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-900">
                                @foreach($requests as $request)
                                    <div class="p-6 space-y-4 hover:bg-slate-900/10 transition-colors">
                                        <!-- Header row -->
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs font-bold text-white capitalize">{{ $request->category }}</span>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase 
                                                        @if($request->priority === 'low') bg-blue-500/15 text-blue-400
                                                        @elseif($request->priority === 'medium') bg-amber-500/15 text-amber-400
                                                        @elseif($request->priority === 'high') bg-orange-500/15 text-orange-400
                                                        @else bg-red-500/15 text-red-400
                                                        @endif">
                                                        {{ $request->priority }}
                                                    </span>
                                                </div>
                                                <span class="text-[9px] text-slate-500 block mt-0.5">Submitted {{ $request->created_at->diffForHumans() }}</span>
                                            </div>

                                            <!-- Status badge -->
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                                                @if($request->status === 'open') bg-rose-500/15 text-rose-450 border border-rose-500/20
                                                @elseif($request->status === 'in_progress') bg-amber-500/15 text-amber-400 border border-amber-500/20
                                                @else bg-emerald-500/15 text-emerald-400 border border-emerald-500/20
                                                @endif">
                                                {{ $request->status === 'in_progress' ? 'Active' : $request->status }}
                                            </span>
                                        </div>

                                        <!-- Description snippet -->
                                        <p class="text-xs text-slate-400 font-mono leading-relaxed bg-slate-950/40 p-3 rounded-xl border border-slate-900">
                                            {{ $request->description }}
                                        </p>

                                        <!-- Image Attachment indicator -->
                                        @if($request->photo_path)
                                            <div class="flex items-center space-x-1.5 text-[10px] text-indigo-400">
                                                <span>📸</span>
                                                <a href="{{ asset('storage/' . $request->photo_path) }}" target="_blank" class="hover:underline font-semibold">View uploaded photo</a>
                                            </div>
                                        @endif

                                        <!-- Live Landlord Note Timeline step -->
                                        @if($request->notes)
                                            <div class="bg-indigo-650 bg-indigo-900/20 border border-indigo-500/15 p-4 rounded-2xl space-y-2">
                                                <div class="flex items-center justify-between text-[10px] font-bold text-indigo-400">
                                                    <span>Landlord Response</span>
                                                    <span>Live Update</span>
                                                </div>
                                                <p class="text-xs text-slate-300 leading-relaxed font-sans">
                                                    "{{ $request->notes }}"
                                                </p>
                                            </div>
                                        @else
                                            <div class="p-3 bg-slate-950/50 rounded-xl text-center text-[10px] text-slate-550 border border-slate-900">
                                                ⏳ Awaiting landlord's scheduled logs
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

            </div>

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

    </body>
</html>
