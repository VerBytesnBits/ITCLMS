<div class="p-4">
    <button wire:click="toggle" class="px-4 py-2 bg-emerald-600 text-white rounded">
        {{ $show ? 'Hide' : 'Show' }} Decommissioned Units
    </button>

    @if($show)
    <div class="mt-4 overflow-auto">
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Unit Name</th>
                    <th class="border p-2">Deleted At</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                <tr class="cursor-pointer hover:bg-gray-50">
                    <td class="border p-2" wire:click="toggleUnitExpansion({{ $unit['id'] }})">
                        {{ $unit['name'] ?? 'Unnamed' }}
                    </td>
                    <td class="border p-2">{{ $unit['deleted_at'] ?? 'N/A' }}</td>
                    <td class="border p-2">
                        <button wire:click="restoreUnitOrChildren({{ $unit['id'] }})"
                                class="px-2 py-1 bg-emerald-600 text-white rounded text-xs">
                            Restore
                        </button>
                    </td>
                </tr>

                @if(in_array($unit['id'], $expandedUnits))
                <tr>
                    <td colspan="3" class="border p-2 bg-gray-50">
                        <div class="mb-2">
                            <h4 class="font-medium">Components</h4>
                            @foreach($unit['components'] ?? [] as $component)
                            <label class="inline-flex items-center mr-4">
                                <input type="checkbox"
                                       wire:model="selectedComponents.{{ $unit['id'] }}"
                                       value="{{ $component['id'] }}">
                                <span class="ml-1">{{ $component['part'] ?? 'N/A' }} ({{ $component['serial_number'] ?? 'N/A' }})</span>
                            </label>
                            @endforeach
                        </div>
                        <div>
                            <h4 class="font-medium">Peripherals</h4>
                            @foreach($unit['peripherals'] ?? [] as $peripheral)
                            <label class="inline-flex items-center mr-4">
                                <input type="checkbox"
                                       wire:model="selectedPeripherals.{{ $unit['id'] }}"
                                       value="{{ $peripheral['id'] }}">
                                <span class="ml-1">{{ $peripheral['name'] ?? 'N/A' }} ({{ $peripheral['serial_number'] ?? 'N/A' }})</span>
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
    @endif
</div>
