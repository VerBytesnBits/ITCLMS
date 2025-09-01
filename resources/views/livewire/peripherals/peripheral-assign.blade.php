<div class="p-4">
    <h2 class="text-lg font-bold mb-4">Assign Peripherals to {{ $unit->name }}</h2>

    <div class="space-y-4">
        {{-- Mouse --}}
        <div>
            <label class="block text-sm font-medium">Mouse</label>
            <select wire:model="selectedPeripherals.mouse" class="w-full border rounded">
                <option value="">-- Select Mouse --</option>
                @foreach ($peripheralsByType['mouse'] as $mouse)
                    <option value="{{ $mouse->id }}">
                        {{ $mouse->serial_number ?? 'No Serial' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Keyboard --}}
        <div>
            <label class="block text-sm font-medium">Keyboard</label>
            <select wire:model="selectedPeripherals.keyboard" class="w-full border rounded">
                <option value="">-- Select Keyboard --</option>
                @foreach ($peripheralsByType['keyboard'] as $keyboard)
                    <option value="{{ $keyboard->id }}">
                        {{ $keyboard->serial_number ?? 'No Serial' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Monitor --}}
        <div>
            <label class="block text-sm font-medium">Monitor</label>
            <select wire:model="selectedPeripherals.monitor" class="w-full border rounded">
                <option value="">-- Select Monitor --</option>
                @foreach ($peripheralsByType['monitor'] as $monitor)
                    <option value="{{ $monitor->id }}">
                        {{ $monitor->serial_number ?? 'No Serial' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex justify-end mt-6 space-x-2">
        <button type="button"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                wire:click="$dispatch('closeModal')">
            Cancel
        </button>
        <button type="button"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                wire:click="assign">
            Assign
        </button>
    </div>
</div>
