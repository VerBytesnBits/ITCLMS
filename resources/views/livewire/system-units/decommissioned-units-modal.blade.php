<div x-data="{ open: @entangle('show') }" x-show="open" x-cloak
    class="fixed inset-0 z-50 overflow-auto bg-black/50 flex justify-center items-start pt-16">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-4xl p-6" x-transition>
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Decommissioned Units</h2>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto">
            @forelse ($units as $unit)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-center cursor-pointer"
                        wire:click="toggleUnitExpansion({{ $unit->id }})">
                        <h3 class="font-medium text-gray-700 dark:text-gray-200">
                            {{ $unit->name ?? 'Unnamed Unit' }}
                        </h3>
                        <span>
                            @if (in_array($unit->id, $expandedUnits))
                                ▼
                            @else
                                ▶
                            @endif
                        </span>
                    </div>

                    @if (in_array($unit->id, $expandedUnits))
                        <div class="mt-3 space-y-2">
                            <!-- Components -->
                            <div>
                                <h4 class="font-semibold text-gray-600 dark:text-gray-300">Components</h4>
                                <div class="space-y-1">
                                    @foreach ($unit->components as $component)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" wire:model="selectedComponents.{{ $unit->id }}"
                                                value="{{ $component->id }}" class="rounded border-gray-300">
                                            <span>{{ $component->part }} - {{ $component->serial_number }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Peripherals -->
                            <div>
                                <h4 class="font-semibold text-gray-600 dark:text-gray-300">Peripherals</h4>
                                <div class="space-y-1">
                                     @foreach ($unit->peripherals as $peripheral)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" wire:model="selectedPeripherals.{{ $unit->id }}"
                                                value="{{ $peripheral->id }}" class="rounded border-gray-300">
                                            <span>{{ $peripheral->name }} - {{ $peripheral->serial_number }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-3 flex justify-end gap-2">
                                <button wire:click="restoreSelectedChildren({{ $unit->id }})"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-medium">
                                    Restore Selected
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No decommissioned units found.</p>
            @endforelse
        </div>

        <div class="mt-4 flex justify-end">
            <button @click="open = false"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium">
                Close
            </button>
        </div>
    </div>
</div
