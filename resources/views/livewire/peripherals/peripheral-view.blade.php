<div 
    x-data 
    x-on:keydown.escape.window="$dispatch('closeModal')" 
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-all"
>
    <!-- Modal Container -->
    <div 
        class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden border border-zinc-200 dark:border-zinc-700"
    >
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <flux:icon.monitor class="w-6 h-6 text-white" />
                <h2 class="text-lg font-semibold text-white tracking-wide">
                    Peripheral Details
                </h2>
            </div>
            <button 
                wire:click="$dispatch('closeModal')" 
                class="text-white/80 hover:text-white transition transform hover:scale-110"
            >
                ✕
            </button>
        </div>

        <!-- Body -->
        <div class="px-8 py-8 space-y-8 text-sm text-gray-700 dark:text-gray-100">

            <!-- Section 1: Basic Information -->
            <div>
                <h3 class="flex items-center gap-2 mb-3 text-base font-semibold text-gray-900 dark:text-white">
                    <flux:icon.info class="w-5 h-5 text-blue-500" />
                    Basic Information
                </h3>
                <div class="grid sm:grid-cols-2 gap-4 bg-gray-50 dark:bg-zinc-900/40 p-4 rounded-xl border border-gray-100 dark:border-zinc-700">
                    <x-detail label="Type" :value="$peripheral->type ?? '—'" />
                    <x-detail label="Serial Number" :value="$peripheral->serial_number ?? '—'" />
                    <x-detail label="Brand" :value="$peripheral->brand ?? '—'" />
                    <x-detail label="Model" :value="$peripheral->model ?? '—'" />
                    <x-detail label="Assigned Unit" :value="optional($peripheral->systemUnit)->name ?? '—'" />
                </div>
            </div>

            <!-- Section 2: Status & Condition -->
            <div>
                <h3 class="flex items-center gap-2 mb-3 text-base font-semibold text-gray-900 dark:text-white">
                    <flux:icon.activity class="w-5 h-5 text-blue-500" />
                    Status
                </h3>
                <div class="grid sm:grid-cols-2 gap-4 bg-gray-50 dark:bg-zinc-900/40 p-4 rounded-xl border border-gray-100 dark:border-zinc-700">
                    <div>
                        <span class="font-medium">Status:</span>
                        <span
                            class="ml-2 px-3 py-1 text-xs font-medium rounded-full shadow-sm 
                            @class([
                                'bg-green-100 text-green-700' => $peripheral->status === 'Available',
                                'bg-yellow-100 text-yellow-700' => $peripheral->status === 'In Use',
                                'bg-red-100 text-red-700' => $peripheral->status === 'Defective',
                                'bg-gray-100 text-gray-700' => $peripheral->status === 'Maintenance',
                            ])"
                        >
                            {{ $peripheral->status ?? '—' }}
                        </span>
                    </div>
                    {{-- <div>
                        <span class="font-medium">Condition:</span>
                        <span
                            class="ml-2 px-3 py-1 text-xs font-medium rounded-full shadow-sm 
                            @class([
                                'bg-blue-100 text-blue-700' => $peripheral->condition === 'Excellent',
                                'bg-green-100 text-green-700' => $peripheral->condition === 'Good',
                                'bg-yellow-100 text-yellow-700' => $peripheral->condition === 'Fair',
                                'bg-red-100 text-red-700' => $peripheral->condition === 'Poor',
                            ])"
                        >
                            {{ $peripheral->condition ?? '—' }}
                        </span>
                    </div> --}}
                </div>
            </div>

            <!-- Section 3: Location -->
            @if ($peripheral->room)
                <div>
                    <h3 class="flex items-center gap-2 mb-3 text-base font-semibold text-gray-900 dark:text-white">
                        <flux:icon.map-pin class="w-5 h-5 text-blue-500" />
                        Location
                    </h3>
                    <div class="bg-gray-50 dark:bg-zinc-900/40 p-4 rounded-xl border border-gray-100 dark:border-zinc-700">
                        <span class="font-medium">Room:</span> {{ $peripheral->room->name }}
                    </div>
                </div>
            @endif

            <!-- Barcode -->
            @if ($peripheral->barcode_path)
                <div class="flex flex-col items-center justify-center mt-6">
                    <img src="{{ asset($peripheral->barcode_path) }}" alt="Barcode" class="h-20">
                    <p class="text-xs text-gray-500 mt-1">Scan to verify</p>
                </div>
            @endif

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700 flex justify-end">
            <button 
                wire:click="$dispatch('closeModal')" 
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm transition font-medium"
            >
                Close
            </button>
        </div>
    </div>
</div>
