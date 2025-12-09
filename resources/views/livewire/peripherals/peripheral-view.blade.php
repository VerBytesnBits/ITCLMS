<div x-data x-on:keydown.escape.window="$dispatch('closeModal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70 backdrop-blur-sm">

    <div x-transition
        class="w-full max-w-2xl mx-4 bg-white dark:bg-zinc-900 rounded-xl shadow-2xl overflow-hidden animate-[fade-in-scale_0.25s_ease-out]">

        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white border-b border-blue-900 dark:border-blue-700">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        
                        {{ $peripheral->type ?? 'Peripheral' }} Details
                    </h2>
                    <p class="text-sm font-light text-blue-200 mt-0.5">
                        {{ $peripheral->brand ?? '—' }} / **{{ $peripheral->model ?? '—' }}**
                    </p>
                </div>
                <button wire:click="$dispatch('closeModal')" class="p-2 -mr-2 rounded-full transition hover:bg-red-500/70"
                    title="Close">
                    <flux:icon.x class="w-5 h-5 text-white" />
                </button>
            </div>
            
            <div class="mt-4 flex items-center gap-4">
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full 
                    {{ $peripheral->status === 'Available' ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-300' : '' }}
                    {{ $peripheral->status === 'In Use' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-300' : '' }}
                    {{ $peripheral->status === 'Defective' ? 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-300' : '' }}
                    {{ $peripheral->status === 'Maintenance' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : '' }}">
                    <flux:icon.activity class="w-4 h-4 mr-1" />
                    STATUS: {{ $peripheral->status ?? '—' }}
                </span>
                
                <p class="text-sm text-blue-200 hidden sm:block">
                    Assigned to: **{{ optional($peripheral->systemUnit)->name ?? 'Unassigned' }}**
                </p>
            </div>
        </div>

        <div class="p-6 space-y-6 text-sm text-gray-800 dark:text-gray-200">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                
                <div class="space-y-4">
                    <h3 class="flex items-center gap-2 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider mb-2">
                        <flux:icon.info class="w-4 h-4 text-blue-500" /> IDENTITY & SPECS
                    </h3>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-500 dark:text-gray-400">Peripheral Type</span>
                        <span class="text-base">{{ $peripheral->type ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-500 dark:text-gray-400">Serial Number</span>
                        <span class="text-base font-mono bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded">{{ $peripheral->serial_number ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-500 dark:text-gray-400">Brand / Model</span>
                        <span class="text-base">{{ $peripheral->brand ?? '—' }} / {{ $peripheral->model ?? '—' }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="flex items-center gap-2 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider mb-2">
                        <flux:icon.map-pin class="w-4 h-4 text-emerald-500" /> LOCATION & ASSIGNMENT
                    </h3>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-500 dark:text-gray-400">Assigned Unit</span>
                        <span class="text-base font-semibold">{{ optional($peripheral->systemUnit)->name ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-500 dark:text-gray-400">Current Room</span>
                        <span class="text-base">{{ optional($peripheral->room)->name ?? '—' }}</span>
                    </div>
                    
                </div>
            </div>

            <div class="p-4 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700">
                <h3 class="flex items-center gap-2 mb-3 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                    <flux:icon.badge-check class="w-4 h-4 text-amber-500" /> LIFECYCLE
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <div class="flex flex-col">
                        <span class="font-medium mb-1">Warranty Status:</span>
                        @if ($peripheral->warranty_expires_at)
                            @if ($peripheral->warranty_remaining_days > 0)
                                <div class="flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-lg 
                                    bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-300">
                                    <flux:icon.circle-check class="w-4 h-4 text-green-600 dark:text-green-400" />
                                    Expires: {{ $peripheral->warranty_expires_at->format('M d, Y') }} ({{ $peripheral->warranty_remaining_days }} days left)
                                </div>
                            @elseif ($peripheral->warranty_remaining_days === 0)
                                <div class="flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-lg 
                                    bg-yellow-100 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-300">
                                    <flux:icon.triangle-alert class="w-4 h-4 text-yellow-600 dark:text-yellow-400" />
                                    Expires today! ({{ $peripheral->warranty_expires_at->format('M d, Y') }})
                                </div>
                            @else
                                <div class="flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-lg 
                                    bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-300">
                                    <flux:icon.circle-x class="w-4 h-4 text-red-600 dark:text-red-400" />
                                    Expired: {{ abs($peripheral->warranty_remaining_days) }} days ago
                                </div>
                            @endif
                        @else
                            <span class="text-gray-500">No warranty information available</span>
                        @endif
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="font-medium mb-1">Purchase Date:</span>
                        <div class="flex items-center gap-1">
                            <flux:icon.calendar class="w-4 h-4 text-indigo-500" />
                            <span class="font-medium text-base">{{ $peripheral->purchase_date ? $peripheral->purchase_date->format('M d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="bg-gray-50 dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700 p-4 flex flex-col sm:flex-row items-center justify-between">
            
            <div class="flex items-center gap-4 py-2">
                <div class="flex flex-col items-center">
                    <img src="{{ asset($peripheral->barcode_path) }}" alt="Barcode" 
                        class="h-10 w-auto object-contain bg-white p-1 border border-gray-300 rounded" loading="lazy">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
                        Scan to verify
                    </p>
                </div>
                <div class="border-l border-gray-300 dark:border-zinc-700 h-10 hidden sm:block"></div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">
                    Asset Tag: **{{ $peripheral->serial_number ?? '—' }}**
                </p>
            </div>

            <div class="flex justify-end gap-3 mt-4 sm:mt-0">
                {{-- <button 
                    class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition shadow-md">
                    <flux:icon.edit-3 class="inline w-4 h-4 mr-1"/> Edit Peripheral
                </button> --}}
                <button wire:click="$dispatch('closeModal')"
                    class="px-5 py-2.5 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-700 dark:text-gray-200 text-sm font-medium transition">
                    Close
                </button>
            </div>
        </div>

    </div>
</div>