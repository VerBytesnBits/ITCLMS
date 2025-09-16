<div x-data x-on:keydown.escape.window="$dispatch('closeModal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg p-6 w-full max-w-2xl"
         x-transition>
         
        <h2 class="text-xl font-bold mb-4">Computer Unit Details</h2>

        @if($unit)
            <div class="space-y-2">
                <p><strong>Unit Name:</strong> {{ $unit['name'] }}</p>
                <p><strong>Serial:</strong> {{ $unit['serial_number'] ?? 'N/A' }}</p>
                <p><strong>Room:</strong> {{ $unit['room']->name ?? 'N/A' }}</p>

            </div>

            <!-- Components Accordion -->
            <div class="mt-4" x-data="{ openComponents: false }">
                <button @click="openComponents = !openComponents"
                        class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700">
                    <span class="font-semibold">Components</span>
                    <svg x-show="!openComponents" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <svg x-show="openComponents" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>
                <div x-show="openComponents" x-collapse class="divide-y divide-gray-200 dark:divide-zinc-700 mt-2">
                    @foreach($unit['components'] as $component)
                        <div class="py-2">
                            <p>
                                <strong>{{ $component['part'] }}</strong> - 
                                {{ $component['brand'] }} {{ $component['model'] }} ({{ $component['serial_number'] }}) 
                                - {{ $component['status'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Peripherals Accordion -->
            <div class="mt-4" x-data="{ openPeripherals: false }">
                <button @click="openPeripherals = !openPeripherals"
                        class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700">
                    <span class="font-semibold">Peripherals</span>
                    <svg x-show="!openPeripherals" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <svg x-show="openPeripherals" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>
                <div x-show="openPeripherals" x-collapse class="divide-y divide-gray-200 dark:divide-zinc-700 mt-2">
                    @foreach($unit['peripherals'] as $peripheral)
                        <div class="py-2">
                            <p>
                                <strong>{{ $peripheral['type'] }}</strong> - 
                                {{ $peripheral['brand'] }} {{ $peripheral['model'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end">
            <button wire:click="$dispatch('closeModal')"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>
