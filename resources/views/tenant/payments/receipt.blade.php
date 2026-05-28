<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $payment->reference_code }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            body {
                background: white;
                color: black;
            }
            .no-print {
                display: none !important;
            }
            .print-shadow-none {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen py-12 px-4 md:px-0">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Print Header / Actions -->
        <div class="flex items-center justify-between no-print bg-slate-900/60 border border-slate-800 rounded-2xl px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-slate-400">
                <a href="{{ url()->previous() }}" class="hover:text-white transition-colors">← Back</a>
                <span>/</span>
                <span class="text-slate-200">Receipt Details</span>
            </div>
            <button 
                onclick="window.print()"
                class="inline-flex items-center justify-center px-4 py-2 border border-slate-700 hover:border-slate-500 rounded-xl text-sm font-semibold text-white bg-slate-800 hover:bg-slate-750 transition-all cursor-pointer"
            >
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Receipt
            </button>
        </div>

        <!-- Central Invoice Area -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 md:p-12 shadow-[0_20px_50px_rgba(0,0,0,0.5)] print-shadow-none print:bg-white print:text-black">
            <!-- Top Branding & Transaction Block -->
            <div class="flex flex-col md:flex-row md:items-start md:justify-between space-y-6 md:space-y-0 border-b border-slate-800 pb-8 print:border-slate-200">
                <div>
                    <div class="flex items-center space-x-2">
                        <div class="h-9 w-9 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white text-base">LD</div>
                        <span class="text-xl font-extrabold tracking-tight text-white font-outfit print:text-black">LandForDays</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 print:text-slate-600">
                        Premium Digital Property Management
                    </p>
                </div>
                <div class="text-left md:text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 print:bg-emerald-100 print:text-emerald-800">
                        Paid Successfully
                    </span>
                    <h3 class="text-sm font-bold text-slate-300 mt-3 print:text-slate-700">Receipt Code</h3>
                    <div class="text-lg font-extrabold text-white font-outfit print:text-black">{{ $payment->reference_code }}</div>
                    <div class="text-xs text-slate-400 mt-1 print:text-slate-600">
                        Date: {{ $payment->payment_date->format('M d, Y') }}
                    </div>
                </div>
            </div>

            <!-- Billing Address Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-b border-slate-800 print:border-slate-200">
                <!-- From Landlord -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider print:text-slate-600">Property Details</h4>
                    <div>
                        <div class="text-base font-extrabold text-white font-outfit print:text-black">
                            {{ $payment->lease->unit->property->name }}
                        </div>
                        <div class="text-sm text-slate-300 mt-1 print:text-slate-800">
                            Unit {{ $payment->lease->unit->unit_number }}
                        </div>
                        <div class="text-xs text-slate-400 mt-0.5 print:text-slate-600">
                            {{ $payment->lease->unit->property->address }}, {{ $payment->lease->unit->property->city }}
                        </div>
                    </div>
                </div>

                <!-- Billed To Tenant -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider print:text-slate-600">Billed To Tenant</h4>
                    <div>
                        <div class="text-base font-extrabold text-white font-outfit print:text-black">
                            {{ $payment->lease->tenant->name }}
                        </div>
                        <div class="text-sm text-slate-300 mt-1 print:text-slate-800">
                            {{ $payment->lease->tenant->email }}
                        </div>
                        <div class="text-xs text-slate-400 mt-0.5 print:text-slate-600">
                            Lease ID: #{{ $payment->lease->id }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Itemized Details Table -->
            <div class="py-8">
                <table class="min-w-full text-left text-sm divide-y divide-slate-800 print:divide-slate-200">
                    <thead class="text-slate-400 uppercase font-bold text-xs tracking-wider print:text-slate-600">
                        <tr>
                            <th class="py-3">Description</th>
                            <th class="py-3 text-right">Payment Method</th>
                            <th class="py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-200 print:divide-slate-200 print:text-black">
                        <tr>
                            <td class="py-4">
                                <div class="font-semibold">Rent Payment</div>
                                <div class="text-xs text-slate-400 mt-0.5 print:text-slate-600">
                                    Term: {{ $payment->lease->start_date->format('M Y') }}
                                </div>
                            </td>
                            <td class="py-4 text-right">
                                <span class="text-slate-300 print:text-slate-800">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td class="py-4 text-right font-bold text-emerald-400 print:text-emerald-800">
                                ₦{{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                        <!-- Total -->
                        <tr class="border-t-2 border-slate-800 print:border-slate-200">
                            <td colspan="2" class="py-4 font-bold text-right text-slate-400 print:text-slate-600">Total Paid</td>
                            <td class="py-4 text-right font-extrabold text-2xl text-white font-outfit print:text-black">
                                ₦{{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Message -->
            <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500 print:border-slate-200 print:text-slate-600">
                <p>Thank you for choosing LandForDays. If you have any inquiries regarding this receipt, please contact support.</p>
                <p class="mt-2">© {{ date('Y') }} LandForDays Inc. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
