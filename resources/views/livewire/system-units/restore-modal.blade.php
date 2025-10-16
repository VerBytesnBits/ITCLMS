<div
    x-data="{ open: @entangle('show') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg w-full max-w-3xl text-left"
        x-transition>
        
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
            Restore Decommissioned Unit
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mb-4">
            Unit: <span class="font-semibold">{{ $unit->name ?? '' }}</span>
        </p>

        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model="restoreParent" class="form-checkbox">
                Restore parent unit
            </label>
        </div>

        <!-- Components -->
        <div class="mb-4">
            <h3 class="font-semibold mb-2">Components</h3>
            @if($unit?->components->count())
                <ul class="space-y-1 max-h-48 overflow-y-auto border p-2 rounded">
                    @foreach($unit->components as $component)
                        <li class="flex justify-between items-center">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="restoreComponents" value="{{ $component->id }}">
                                <span>{{ $component->part }} - {{ $component->serial_number }}</span>
                            </label>

                            <select wire:model="childNewUnit.{{ $component->id }}"
                                    class="border rounded px-1 text-sm">
                                <option value="{{ $unit->id }}">Restore to same unit</option>
                                @foreach(\App\Models\SystemUnit::all() as $su)
                                    <option value="{{ $su->id }}">{{ $su->name }}</option>
                                @endforeach
                            </select>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">No decommissioned components.</p>
            @endif
        </div>

        <!-- Peripherals -->
        <div class="mb-4">
            <h3 class="font-semibold mb-2">Peripherals</h3>
            @if($unit?->peripherals->count())
                <ul class="space-y-1 max-h-48 overflow-y-auto border p-2 rounded">
                    @foreach($unit->peripherals as $peripheral)
                        <li class="flex justify-between items-center">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="restorePeripherals" value="{{ $peripheral->id }}">
                                <span>{{ $peripheral->name }} - {{ $peripheral->serial_number }}</span>
                            </label>

                            <select wire:model="childNewUnit.{{ $peripheral->id }}"
                                    class="border rounded px-1 text-sm">
                                <option value="{{ $unit->id }}">Restore to same unit</option>
                                @foreach(\App\Models\SystemUnit::all() as $su)
                                    <option value="{{ $su->id }}">{{ $su->name }}</option>
                                @endforeach
                            </select>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">No decommissioned peripherals.</p>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 mt-4">
            <button wire:click="cancel" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">Cancel</button>
            <button wire:click="restoreSelected" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-sm">
                Restore Selected
            </button>
        </div>

    </div>
</div>
