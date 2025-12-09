<div>
    <flux:button wire:click="toggle" 
         class="text-white bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-yellow-300 
           dark:focus:ring-yellow-800 shadow-lg shadow-yellow-500/50 
           dark:shadow-lg dark:shadow-yellow-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
        <flux:icon.archive-restore class="w-5 h-5 text-white" />

        <!-- Tooltip (Left Side) -->
        <span
            class="absolute left-[-11rem] top-1/2 -translate-y-1/2 whitespace-nowrap
                 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0
                 group-hover:opacity-100 transition duration-200 pointer-events-none
                 shadow-lg">
            {{ $show ? 'Close Decommissioned Units' : 'View Decommissioned Units' }}
        </span>
    </flux:button>


    <!-- Modal -->
    @if ($show)
        <div x-data x-init="$el.querySelector('[data-modal]').focus()" x-on:keydown.escape.window="$wire.toggle()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div data-modal
                class="w-full max-w-5xl bg-white dark:bg-zinc-900 rounded-2xl shadow-xl overflow-hidden  dark:border-zinc-700"
                tabindex="0">

                <!-- Header -->
                <div
                    class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-blue-700 via-blue-500 to-blue-400 dark:from-blue-300 dark:to-blue-900 text-white border-b border-blue-500">
                    <div>
                        <h2 class="text-lg font-semibold">Decommissioned Units</h2>
                        <p class="text-xs opacity-90">List of units available for restoration</p>
                    </div>
                    <button wire:click="toggle" class="p-2 rounded-full hover:bg-red-500 transition">
                        <flux:icon.x class="w-5 h-5" />
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 max-h-[70vh] overflow-auto">
                    <table class="w-full border-collapse border rounded-lg text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 text-zinc-800">
                                <th class="border p-2 text-left">Unit Name</th>
                                <th class="border p-2 text-left">Deleted At</th>
                                <th class="border p-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr class="cursor-pointer hover:bg-blue-50 transition">
                                    <td class="border p-2" wire:click="toggleUnitExpansion({{ $unit['id'] }})">
                                        {{ $unit['name'] ?? 'Unnamed' }}
                                    </td>
                                    <td class="border p-2">{{ $unit['deleted_at'] ?? 'N/A' }}</td>
                                    <td class="border p-2">
                                        <button wire:click="restoreUnitOrChildren({{ $unit['id'] }})"
                                            class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs transition">
                                            Restore
                                        </button>
                                    </td>
                                </tr>

                                @if (in_array($unit['id'], $expandedUnits))
                                    <tr>
                                        <td colspan="3" class="border p-2 bg-gray-50 dark:bg-zinc-800">
                                            <div class="mb-2">
                                                <h4 class="font-medium">Components</h4>
                                                @foreach ($unit['components'] ?? [] as $component)
                                                    <label class="inline-flex items-center mr-4">
                                                        <input type="checkbox"
                                                            wire:model="selectedComponents.{{ $unit['id'] }}"
                                                            value="{{ $component['id'] }}">
                                                        <span class="ml-1">{{ $component['part'] ?? 'N/A' }}
                                                            ({{ $component['serial_number'] ?? 'N/A' }})
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <div>
                                                <h4 class="font-medium">Peripherals</h4>
                                                @foreach ($unit['peripherals'] ?? [] as $peripheral)
                                                    <label class="inline-flex items-center mr-4">
                                                        <input type="checkbox"
                                                            wire:model="selectedPeripherals.{{ $unit['id'] }}"
                                                            value="{{ $peripheral['id'] }}">
                                                        <span class="ml-1">{{ $peripheral['name'] ?? 'N/A' }}
                                                            ({{ $peripheral['serial_number'] ?? 'N/A' }})
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div
                    class="flex justify-end items-center gap-2 px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                    <button wire:click="toggle"
                        class="px-4 py-2 bg-zinc-300 hover:bg-zinc-400 text-zinc-800 rounded-lg transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
