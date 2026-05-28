<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Onboarding & Lease Setup - LandForDays</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] {
                display: none !important;
            }
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
            .glow-effect {
                background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.15) 0%, rgba(0, 0, 0, 0) 50%);
            }
        </style>
    </head>
    <body class="min-h-full py-12 antialiased text-slate-100 glow-effect bg-slate-950 flex items-center justify-center p-4">
        
        <div class="w-full max-w-5xl bg-slate-900/60 border border-slate-900 rounded-3xl overflow-hidden shadow-2xl backdrop-blur-md flex flex-col md:flex-row min-h-[600px]">
            
            <!-- Left Info Panel: Lease Highlights -->
            <div class="w-full md:w-5/12 bg-gradient-to-br from-indigo-950/80 to-slate-950 p-8 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-900 text-slate-350">
                <div class="space-y-6">
                    <!-- Brand -->
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-indigo-650 bg-indigo-600 rounded-lg text-white font-bold text-md shadow">
                            LD
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">Land<span class="text-indigo-400">ForDays</span></span>
                    </div>

                    <div class="pt-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                            🔑 Digital Lease invitation
                        </span>
                        <h2 class="mt-4 text-3xl font-extrabold text-white leading-tight">Welcome to your new home</h2>
                        <p class="mt-2 text-sm text-slate-450 leading-relaxed">
                            You've been invited by your landlord to register your profile and activate your lease agreement.
                        </p>
                    </div>

                    <!-- Property card highlight -->
                    <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl space-y-3 shadow-inner">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Property details</span>
                            <h4 class="text-base font-bold text-white mt-0.5">{{ $invite->unit->property->name }}</h4>
                            <p class="text-xs text-slate-550 mt-0.5">{{ $invite->unit->property->address }}, {{ $invite->unit->property->city }}</p>
                        </div>
                        
                        <div class="border-t border-slate-800/80 pt-3 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest block">Unit</span>
                                <span class="text-sm font-bold text-indigo-400">Unit {{ $invite->unit->unit_number }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest block">Monthly rent</span>
                                <span class="text-sm font-bold text-emerald-400">${{ number_format($invite->monthly_rent, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="pt-6 md:pt-0">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 block mb-1">Lease Terms Duration</span>
                    <p class="text-xs text-slate-400 font-medium">
                        {{ $invite->start_date->format('F d, Y') }} &mdash; {{ $invite->end_date->format('F d, Y') }}
                    </p>
                </div>
            </div>

            <!-- Right Panel: Onboarding Signup Form -->
            <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center">
                <div class="max-w-xl w-full mx-auto space-y-6">
                    <div>
                        <h3 class="text-2xl font-bold text-white tracking-tight">Create your account</h3>
                        <p class="text-xs text-slate-450 mt-1">Please fill in your details to secure your account and bind the lease.</p>
                    </div>

                    @if ($errors->any())
                        <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-450 rounded-2xl space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-xs font-semibold">&bull; {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form 
                        x-data="{ 
                            currentStep: 1, 
                            maxSteps: 5,
                            validateStep(step) {
                                const stepEl = this.$refs['step' + step];
                                if (!stepEl) return true;
                                const inputs = stepEl.querySelectorAll('input, select, textarea');
                                let isValid = true;
                                for (let input of inputs) {
                                    if (!input.checkValidity()) {
                                        input.reportValidity();
                                        isValid = false;
                                        break;
                                    }
                                }
                                return isValid;
                            },
                            nextStep() {
                                if (this.validateStep(this.currentStep)) {
                                    if (this.currentStep < this.maxSteps) {
                                        this.currentStep++;
                                    }
                                }
                            },
                            prevStep() {
                                if (this.currentStep > 1) {
                                    this.currentStep--;
                                }
                            },
                            validateAll() {
                                for (let step = 1; step <= this.maxSteps; step++) {
                                    if (!this.validateStep(step)) {
                                        this.currentStep = step;
                                        return false;
                                    }
                                }
                                return true;
                            }
                        }"
                        @submit="if (!validateAll()) { $event.preventDefault(); }"
                        action="{{ route('tenant.onboard.process', $invite->token) }}" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        class="space-y-6"
                    >
                        @csrf

                        <!-- Step Progress Bar -->
                        <div class="mb-8 select-none">
                            <div class="flex items-center justify-between relative px-2">
                                <!-- Connecting Line -->
                                <div class="absolute left-6 right-6 top-1/2 -translate-y-1/2 h-0.5 bg-slate-800 -z-10 rounded-full">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-500 transition-all duration-500 rounded-full" 
                                         :style="'width: ' + ((currentStep - 1) / (maxSteps - 1)) * 100 + '%'">
                                    </div>
                                </div>

                                <!-- Step Indicators -->
                                <template x-for="step in [1, 2, 3, 4, 5]" :key="step">
                                    <button 
                                        type="button"
                                        @click="if (step < currentStep || validateStep(currentStep)) { currentStep = step }"
                                        class="flex items-center justify-center w-9 h-9 rounded-full border-2 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 z-10 cursor-pointer"
                                        :class="{
                                            'bg-slate-900 border-indigo-500 text-indigo-400 font-bold scale-110 shadow-lg shadow-indigo-500/20': currentStep === step,
                                            'bg-emerald-500 border-emerald-500 text-white font-bold': currentStep > step,
                                            'bg-slate-950 border-slate-800 text-slate-500 hover:border-slate-700': currentStep < step
                                        }"
                                    >
                                        <!-- Checkmark for completed steps -->
                                        <template x-if="currentStep > step">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </template>
                                        <!-- Number for active/pending steps -->
                                        <template x-if="currentStep <= step">
                                            <span class="text-xs" x-text="step"></span>
                                        </template>
                                    </button>
                                </template>
                            </div>

                            <!-- Step Labels -->
                            <div class="flex justify-between mt-3 text-[10px] sm:text-xs font-bold tracking-wider text-slate-500 px-1 uppercase text-center select-none">
                                <span class="w-12 transition-colors duration-300" :class="currentStep === 1 ? 'text-indigo-400' : (currentStep > 1 ? 'text-emerald-400' : 'text-slate-500')">Setup</span>
                                <span class="w-12 transition-colors duration-300" :class="currentStep === 2 ? 'text-indigo-400' : (currentStep > 2 ? 'text-emerald-400' : 'text-slate-500')">Profile</span>
                                <span class="w-12 transition-colors duration-300" :class="currentStep === 3 ? 'text-indigo-400' : (currentStep > 3 ? 'text-emerald-400' : 'text-slate-500')">Home</span>
                                <span class="w-12 transition-colors duration-300" :class="currentStep === 4 ? 'text-indigo-400' : (currentStep > 4 ? 'text-emerald-400' : 'text-slate-500')">Kin</span>
                                <span class="w-12 transition-colors duration-300" :class="currentStep === 5 ? 'text-indigo-400' : (currentStep > 5 ? 'text-emerald-400' : 'text-slate-500')">Tenancy</span>
                            </div>
                        </div>

                        <!-- Form Section 1: Security & Credentials -->
                        <div x-show="currentStep === 1" x-ref="step1" x-transition.opacity.duration.300ms class="space-y-4">
                            <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest border-b border-slate-800 pb-2">1. Security & Credentials</h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Locked Email -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Registered Email</label>
                                    <div class="bg-slate-950 border border-slate-900 rounded-xl px-4 py-3 text-sm text-slate-400 select-none cursor-not-allowed">
                                        {{ $invite->email }}
                                    </div>
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Full Name</label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        required 
                                        value="{{ old('name') }}"
                                        placeholder="John Doe"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4 transition-colors"
                                    >
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        required 
                                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4 transition-colors"
                                    >
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Confirm Password</label>
                                    <input 
                                        type="password" 
                                        name="password_confirmation" 
                                        id="password_confirmation" 
                                        required 
                                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4 transition-colors"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Form Section 2: Personal Profile -->
                        <div x-show="currentStep === 2" x-ref="step2" x-transition.opacity.duration.300ms class="space-y-4" x-cloak>
                            <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest border-b border-slate-800 pb-2">2. Personal Profile</h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="age" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Age</label>
                                    <input 
                                        type="number" 
                                        name="age" 
                                        id="age" 
                                        required 
                                        min="18"
                                        max="120"
                                        value="{{ old('age') }}"
                                        placeholder="25"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="state_of_origin" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">State of Origin</label>
                                    <input 
                                        type="text" 
                                        name="state_of_origin" 
                                        id="state_of_origin" 
                                        required 
                                        value="{{ old('state_of_origin') }}"
                                        placeholder="e.g. FCT, Lagos"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="marital_status" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Marital Status</label>
                                    <select 
                                        name="marital_status" 
                                        id="marital_status" 
                                        required 
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-slate-450 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                        <option value="single" {{ old('marital_status') === 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('marital_status') === 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('marital_status') === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('marital_status') === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="phone_numbers" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Two Phone Numbers</label>
                                    <input 
                                        type="text" 
                                        name="phone_numbers" 
                                        id="phone_numbers" 
                                        required 
                                        value="{{ old('phone_numbers') }}"
                                        placeholder="e.g. +234..., +234..."
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="current_address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Current Abuja Address</label>
                                    <input 
                                        type="text" 
                                        name="current_address" 
                                        id="current_address" 
                                        required 
                                        value="{{ old('current_address') }}"
                                        placeholder="Full address in Abuja"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="permanent_address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Permanent Home Address</label>
                                <input 
                                    type="text" 
                                    name="permanent_address" 
                                    id="permanent_address" 
                                    required 
                                    value="{{ old('permanent_address') }}"
                                    placeholder="Permanent contact home address"
                                    class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                >
                            </div>
                        </div>

                        <!-- Form Section 3: Occupation & Household Details -->
                        <div x-show="currentStep === 3" x-ref="step3" x-transition.opacity.duration.300ms class="space-y-4" x-cloak>
                            <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest border-b border-slate-800 pb-2">3. Occupation & Household</h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="occupation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Profession / Occupation</label>
                                    <input 
                                        type="text" 
                                        name="occupation" 
                                        id="occupation" 
                                        required 
                                        value="{{ old('occupation') }}"
                                        placeholder="e.g. Software Engineer"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="workplace_details" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Workplace Name & Address</label>
                                    <input 
                                        type="text" 
                                        name="workplace_details" 
                                        id="workplace_details" 
                                        required 
                                        value="{{ old('workplace_details') }}"
                                        placeholder="Present workplace and address"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="spouse_names" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Spouse Name(s) to stay with you (Optional)</label>
                                    <input 
                                        type="text" 
                                        name="spouse_names" 
                                        id="spouse_names" 
                                        value="{{ old('spouse_names') }}"
                                        placeholder="e.g. Spouse Name"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="dependants_details" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dependants: Names, Number, Relationship</label>
                                    <input 
                                        type="text" 
                                        name="dependants_details" 
                                        id="dependants_details" 
                                        value="{{ old('dependants_details') }}"
                                        placeholder="e.g. 2 children (Leo & Jane - Son & Daughter)"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Form Section 4: Next of Kin Details -->
                        <div x-show="currentStep === 4" x-ref="step4" x-transition.opacity.duration.300ms class="space-y-4" x-cloak>
                            <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest border-b border-slate-800 pb-2">4. Next of Kin Details</h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="next_of_kin_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Full Name</label>
                                    <input 
                                        type="text" 
                                        name="next_of_kin_name" 
                                        id="next_of_kin_name" 
                                        required 
                                        value="{{ old('next_of_kin_name') }}"
                                        placeholder="Next of Kin Name"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="next_of_kin_relationship" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Relationship</label>
                                    <input 
                                        type="text" 
                                        name="next_of_kin_relationship" 
                                        id="next_of_kin_relationship" 
                                        required 
                                        value="{{ old('next_of_kin_relationship') }}"
                                        placeholder="e.g. Sister, Father"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="next_of_kin_phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Phone Number(s)</label>
                                    <input 
                                        type="text" 
                                        name="next_of_kin_phone" 
                                        id="next_of_kin_phone" 
                                        required 
                                        value="{{ old('next_of_kin_phone') }}"
                                        placeholder="Phone number"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="next_of_kin_occupation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Occupation</label>
                                    <input 
                                        type="text" 
                                        name="next_of_kin_occupation" 
                                        id="next_of_kin_occupation" 
                                        required 
                                        value="{{ old('next_of_kin_occupation') }}"
                                        placeholder="Occupation"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="next_of_kin_workplace" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Workplace Address</label>
                                    <input 
                                        type="text" 
                                        name="next_of_kin_workplace" 
                                        id="next_of_kin_workplace" 
                                        value="{{ old('next_of_kin_workplace') }}"
                                        placeholder="Workplace name & address"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="next_of_kin_address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Residential Address</label>
                                <input 
                                    type="text" 
                                    name="next_of_kin_address" 
                                    id="next_of_kin_address" 
                                    required 
                                    value="{{ old('next_of_kin_address') }}"
                                    placeholder="Residential Address"
                                    class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                >
                            </div>
                        </div>

                        <!-- Form Section 5: Tenancy & Verification -->
                        <div x-show="currentStep === 5" x-ref="step5" x-transition.opacity.duration.300ms class="space-y-4" x-cloak>
                            <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest border-b border-slate-800 pb-2">5. Tenancy & Verification</h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="expected_duration" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Expected Tenancy Duration</label>
                                    <input 
                                        type="text" 
                                        name="expected_duration" 
                                        id="expected_duration" 
                                        required 
                                        value="{{ old('expected_duration') }}"
                                        placeholder="e.g. 1 Year, 2 Years"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                                <div>
                                    <label for="rent_offer" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Rent Offer Amount (₦)</label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        name="rent_offer" 
                                        id="rent_offer" 
                                        required 
                                        value="{{ old('rent_offer', $invite->monthly_rent) }}"
                                        placeholder="₦1200.00"
                                        class="block w-full rounded-xl border-slate-900 bg-slate-950 text-white placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="id_proof" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Attach Valid ID Proof (PDF or Image)</label>
                                <input 
                                    type="file" 
                                    name="id_proof" 
                                    id="id_proof" 
                                    required 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="block w-full rounded-xl border border-slate-900 bg-slate-950 text-slate-400 text-xs focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4"
                                >
                                <p class="mt-1 text-[10px] text-slate-500 leading-relaxed">
                                    Attach a government-issued ID card, international passport, or driver's license.
                                </p>
                            </div>

                            <div class="bg-indigo-950/20 border border-indigo-950/40 p-4 rounded-2xl text-[10px] text-slate-400 space-y-1.5">
                                <p class="font-bold text-indigo-400 uppercase tracking-wider">🔒 Confidentiality & Agreement Note</p>
                                <p class="leading-relaxed">
                                    Information provided in this form is treated as confidential and is subject to verification. Completion and submission of this Expression of Interest form represents acceptance of the property's private residential purpose. You will be contacted for negotiations as soon as this is submitted.
                                </p>
                                <p class="font-semibold text-slate-350">
                                    * A guarantor must be provided once the tenancy is established. Agent & Legal Fee: 25%.
                                </p>
                            </div>
                        </div>

                        <!-- Navigation Footer -->
                        <div class="pt-6 border-t border-slate-800/60 flex items-center justify-between gap-4 select-none">
                            <!-- Prev Button -->
                            <button 
                                type="button" 
                                x-show="currentStep > 1" 
                                @click="prevStep()" 
                                class="inline-flex items-center justify-center px-5 py-3 text-xs font-bold uppercase tracking-wider rounded-xl text-slate-400 bg-slate-950 border border-slate-900 hover:bg-slate-900 transition-all duration-200 cursor-pointer"
                                style="display: none;"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back
                            </button>
                            
                            <!-- Space placeholder when Back is hidden -->
                            <div x-show="currentStep === 1"></div>

                            <!-- Next Button -->
                            <button 
                                type="button" 
                                x-show="currentStep < maxSteps" 
                                @click="nextStep()" 
                                class="inline-flex items-center justify-center px-6 py-3.5 text-xs font-bold uppercase tracking-wider rounded-xl text-white bg-indigo-650 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all duration-200 cursor-pointer"
                            >
                                Next Step
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                x-show="currentStep === maxSteps" 
                                class="inline-flex items-center justify-center px-6 py-3.5 text-xs font-bold uppercase tracking-wider rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all duration-200 cursor-pointer"
                                style="display: none;"
                            >
                                Submit & Accept Lease
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
