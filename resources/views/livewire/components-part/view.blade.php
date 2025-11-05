<div x-data x-on:keydown.escape.window="$dispatch('closeModal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div x-transition
        class="w-full max-w-2xl mx-4 bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden
               animate-[fade-in-scale_0.25s_ease-out]">
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 
                   px-6 py-4 flex items-center justify-between text-white">
            <h2 class="text-lg font-semibold tracking-wide flex items-center gap-2">
                <flux:icon.cpu-chip class="w-5 h-5 text-blue-200" />
                Component Details
            </h2>
            <button wire:click="$dispatch('closeModal')" class="p-2 rounded-full transition hover:bg-red-500"
                title="Close">
                <flux:icon.x class="w-5 h-5 text-white" />
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-6 space-y-5 text-sm text-gray-800 dark:text-gray-200">

            <!-- Basic Info -->
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center gap-2 mb-3">
                    <flux:icon.info class="w-4 h-4 text-blue-500" />
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                        Basic Info
                    </h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div><span class="font-medium">Unit:</span> {{ optional($component->systemUnit)->name ?? '—' }}
                    </div>
                    <div><span class="font-medium">Room:</span> {{ optional($component->room)->name ?? '—' }}</div>
                    <div><span class="font-medium">Serial:</span> {{ $component->serial_number }}</div>
                    <div><span class="font-medium">Brand:</span>
                        {{ $component->brand ? ucwords(strtolower($component->brand)) : '—' }}</div>
                    <div><span class="font-medium">Model:</span> {{ $component->model ?? '—' }}</div>
                </div>
            </div>

            <!-- Specifications -->
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center gap-2 mb-3">
                    <flux:icon.settings class="w-4 h-4 text-emerald-500" />
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                        Specifications
                    </h3>
                </div>
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <span class="font-medium">Status:</span>
                        <span
                            class="ml-2 px-2 py-0.5 text-xs rounded-full 
                            {{ $component->status === 'In Use'
                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-300'
                                : ($component->status === 'Defective'
                                    ? 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-300'
                                    : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300') }}">
                            {{ $component->status }}
                        </span>
                    </div>

                    @if ($component->part === 'Storage' || $component->part === 'RAM')
                        <div><span class="font-medium">Capacity:</span> {{ $component->capacity ?? '—' }}</div>
                        <div><span class="font-medium">Type:</span> {{ $component->type ?? '—' }}</div>
                    @endif
                </div>
            </div>

            <!-- Warranty -->
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center gap-2 mb-3">
                    <flux:icon.badge-check class="w-4 h-4 text-amber-500" />
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                        Warranty
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    @if ($component->warranty_expires_at)
                        @if ($component->warranty_remaining_days > 0)
                            <div
                                class="flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-lg 
                                bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-300">
                                <flux:icon.circle-check class="w-4 h-4 text-green-600 dark:text-green-400" />
                                Expires on {{ $component->warranty_expires_at->format('M d, Y') }}
                                ({{ $component->warranty_remaining_days }} days left)
                            </div>
                        @elseif ($component->warranty_remaining_days === 0)
                            <div
                                class="flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-lg 
                                bg-yellow-100 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-300">
                                <flux:icon.triangle-alert class="w-4 h-4 text-yellow-600 dark:text-yellow-400" />
                                Expires today ({{ $component->warranty_expires_at->format('M d, Y') }})
                            </div>
                        @else
                            <div
                                class="flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-lg 
                                bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-300">
                                <flux:icon.circle-x class="w-4 h-4 text-red-600 dark:text-red-400" />
                                Expired {{ abs($component->warranty_remaining_days) }} days ago
                                ({{ $component->warranty_expires_at->format('M d, Y') }})
                            </div>
                        @endif
                    @else
                        <span class="text-gray-500">No warranty information available</span>
                    @endif
                </div>
            </div>

            <!-- Purchase -->
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700">
                <div class="flex items-center gap-2 mb-3">
                    <flux:icon.calendar class="w-4 h-4 text-indigo-500" />
                    <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wider">
                        Purchase
                    </h3>
                </div>
                <div>
                    Purchase Date:
                    <span
                        class="font-medium">{{ $component->purchase_date ? $component->purchase_date->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>
        <!-- Barcode -->
        @if ($component->barcode_path)
            <div class="flex flex-col items-center justify-center mt-6">
                <img src="{{ asset($component->barcode_path) }}" alt="Barcode" class="h-20">
                <p class="text-xs text-gray-500 mt-1">Scan to verify</p>
            </div>
        @endif
        <!-- Footer -->
        <div
            class="px-6 py-4 bg-gray-50 dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700 flex justify-end">
            <button wire:click="$dispatch('closeModal')"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                Close
            </button>
        </div>
    </div>
</div>
