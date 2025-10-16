<div x-data x-on:keydown.escape.window="$dispatch('closeModal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div
        class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-[fade-in-scale_0.2s_ease-out]">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Component Details
            </h2>
            <button wire:click="$dispatch('closeModal')"
                class="p-1 rounded-full text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700 transition">
                ✕
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-6 space-y-6 text-sm text-gray-700 dark:text-gray-200">

            <!-- Basic Info -->
            <div>
                <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2">Basic Info</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><span class="font-medium">Unit:</span> {{ optional($component->systemUnit)->name ?? '—' }}</div>
                    <div><span class="font-medium">Room:</span> {{ optional($component->room)->name ?? '—' }}</div>
                    <div><span class="font-medium">Serial:</span> {{ $component->serial_number }}</div>
                    <div><span class="font-medium">Brand:</span> {{ $component->brand ? ucwords(strtolower($component->brand)) : '—' }}</div>
                    <div><span class="font-medium">Model:</span> {{ $component->model ?? '—' }}</div>
                </div>
            </div>

            <!-- Specifications -->
            <div class="pt-4 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2">Specifications</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><span class="font-medium">Condition:</span> {{ $component->condition }}</div>
                    <div><span class="font-medium">Status:</span> {{ $component->status }}</div>

                    {{-- Optional fields based on type --}}
                    @if($component->part === 'Storage')
                        <div><span class="font-medium">Capacity:</span> {{ $component->capacity ?? '—' }}</div>
                        <div><span class="font-medium">Type:</span> {{ $component->type ?? '—' }}</div>
                    @elseif($component->part === 'RAM')
                        <div><span class="font-medium">Capacity:</span> {{ $component->capacity ?? '—' }}</div>
                        <div><span class="font-medium">Type:</span> {{ $component->type ?? '—' }}</div>
                    @endif
                    {{-- Add more conditional specs per part if needed --}}
                </div>
            </div>

            <!-- Warranty -->
            <div class="pt-4 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2">Warranty</h3>
                <div>
                    @php $expiry = \Carbon\Carbon::parse($component->warranty_expires_at ?? now());
                        $purchase = \Carbon\Carbon::parse($component->purchase_date ?? now());
                     @endphp

                    @if ($expiry->isPast())
                        <span class="text-red-500 font-semibold">
                            Expired on {{ $expiry->format('M d, Y') }}
                        </span>
                    @else
                        <span class="text-green-600">
                            Expires on {{ $expiry->format('M d, Y') }}
                            ({{ now()->diffForHumans($expiry, true) }} left)
                        </span>
                    @endif
                </div>
            </div>
            <div>
                Purchase Date {{ $purchase->format('M d, Y') }}
            </div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end">
            <button wire:click="$dispatch('closeModal')"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>
