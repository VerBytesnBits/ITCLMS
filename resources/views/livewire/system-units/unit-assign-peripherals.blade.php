<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Assign Peripherals to {{ $unit->name }}</h2>

        @foreach ($peripheralsByType as $type => $peripherals)
            <div class="mb-4">
                <label class="block font-medium">{{ ucfirst($type) }}</label>
                <select wire:model="selectedPeripherals.{{ $type }}" class="w-full border rounded">
                    <option value="">-- Select {{ ucfirst($type) }} --</option>
                    @foreach ($peripherals as $p)
                        <option value="{{ $p->id }}">{{ $p->serial_number ?? 'No Serial' }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <div class="flex justify-end mt-6 space-x-2">
            <button type="button"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                    wire:click="$dispatch('closeAssignModal')">
                Cancel
            </button>
            <button type="button"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    wire:click="assign">
                Assign
            </button>
        </div>
    </div>
</div>
