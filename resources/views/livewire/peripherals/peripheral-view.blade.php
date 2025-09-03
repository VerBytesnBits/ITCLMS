<div 
    x-data 
    x-on:keydown.escape.window="$dispatch('closeModal')" 
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
>
    <!-- Modal Container -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Peripheral Details</h2>
            <button wire:click="$dispatch('closeModal')" 
                class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                ✕
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                {{-- <div>
                    <span class="font-medium">ID:</span> {{ $peripheral->id }}
                </div> --}}
                <div>
                    <span class="font-medium">Unit:</span> {{ optional($peripheral->systemUnit)->name ?? '—' }}
                </div>
                <div>
                    <span class="font-medium">Serial Number:</span> {{ $peripheral->serial_number }}
                </div>
                <div>
                    <span class="font-medium">Brand:</span> {{ $peripheral->brand }}
                </div>
                <div>
                    <span class="font-medium">Model:</span> {{ $peripheral->model }}
                </div>
                <div>
                    <span class="font-medium">Type:</span> {{ $peripheral->type }}
                </div>
                <div>
                    <span class="font-medium">Condition:</span> {{ $peripheral->condition }}
                </div>
                <div>
                    <span class="font-medium">Status:</span> {{ $peripheral->status }}
                </div>
                
                {{-- <div class="col-span-2">
                    <span class="font-medium">Room:</span> {{ optional($peripheral->room)->name ?? '—' }}
                </div> --}}
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end">
            <button wire:click="$dispatch('closeModal')" 
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                Close
            </button>
        </div>
    </div>
</div>
