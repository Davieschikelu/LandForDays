@php
    // Prepare the ticket data array for clean JSON conversion in Alpine
    $jsTicket = [
        'id' => $ticket->id,
        'property_name' => $ticket->unit->property->name,
        'unit_number' => $ticket->unit->unit_number,
        'tenant_name' => $ticket->tenant->name,
        'tenant_email' => $ticket->tenant->email,
        'category' => $ticket->category,
        'priority' => $ticket->priority,
        'description' => str_replace(["\r", "\n"], ["", " "], addslashes($ticket->description)),
        'status' => $ticket->status,
        'photo_url' => $ticket->photo_path ? asset('storage/' . $ticket->photo_path) : '',
        'notes' => $ticket->notes ? str_replace(["\r", "\n"], ["", " "], addslashes($ticket->notes)) : '',
        'created_at' => $ticket->created_at->format('M d, Y h:i A')
    ];
@endphp

<div @click="openDetail({{ json_encode($jsTicket) }})" 
     data-search="{{ $ticket->unit->property->name }} Unit {{ $ticket->unit->unit_number }} {{ $ticket->tenant->name }} {{ $ticket->tenant->email }} {{ $ticket->category }} {{ $ticket->priority }} {{ $ticket->description }} {{ $ticket->status }}"
     class="group p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-2xl shadow-sm hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer transition-all duration-200 transform hover:-translate-y-0.5 flex flex-col space-y-4">
    
    <!-- Top Bar: Category & Priority -->
    <div class="flex items-center justify-between">
        <!-- Category Icon & Badge -->
        <div class="flex items-center space-x-2">
            <span class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                @if($ticket->category === 'plumbing')
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                @elseif($ticket->category === 'electrical')
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                @elseif($ticket->category === 'appliance')
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                @elseif($ticket->category === 'hvac')
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                @elseif($ticket->category === 'structural')
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                @else
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                @endif
            </span>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 capitalize">{{ $ticket->category }}</span>
        </div>

        <!-- Priority Tag -->
        <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase rounded-lg tracking-wider
            @if($ticket->priority === 'low') bg-blue-500/10 text-blue-600 dark:text-blue-400
            @elseif($ticket->priority === 'medium') bg-amber-500/10 text-amber-600 dark:text-amber-400
            @elseif($ticket->priority === 'high') bg-orange-500/10 text-orange-600 dark:text-orange-400
            @else bg-red-500/10 text-red-600 dark:text-red-400
            @endif">
            {{ $ticket->priority }}
        </span>
    </div>

    <!-- Middle: Title, Property, Description -->
    <div>
        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
            {{ $ticket->unit->property->name }} - <span class="text-indigo-600 dark:text-indigo-400">Unit {{ $ticket->unit->unit_number }}</span>
        </h3>
        
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-mono line-clamp-2">
            {{ $ticket->description }}
        </p>
    </div>

    <!-- Footer Bar: Meta indicators -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-slate-400 text-xs">
        
        <!-- Attachment / Notes Indicators -->
        <div class="flex items-center space-x-2.5">
            @if($ticket->photo_path)
                <span class="flex items-center text-slate-400 hover:text-indigo-500" title="Has image attachment">
                    <svg class="h-4 w-4 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>1</span>
                </span>
            @endif
            @if($ticket->notes)
                <span class="flex items-center text-emerald-500" title="Has landlord updates">
                    <svg class="h-4 w-4 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </span>
            @endif
        </div>

        <!-- Date / Duration -->
        <div class="text-[10px] text-slate-400" title="{{ $ticket->created_at->format('M d, Y h:i A') }}">
            {{ $ticket->created_at->diffForHumans() }}
        </div>
    </div>
</div>
