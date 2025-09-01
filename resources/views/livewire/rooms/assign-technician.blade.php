<div>
    <div class="p-6">
        <h2 class="text-lg font-semibold mb-4">Assign Technicians to {{ $room->name }}</h2>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Select Technician(s)</label>
            <select wire:model="selectedTechnicianIds" multiple class="w-full rounded border-gray-300">
                @foreach($technicianOptions as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            @error('selectedTechnicianIds') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <button wire:click="$dispatch('closeModal')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                Cancel
            </button>
            <button wire:click="save" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">
                Save
            </button>
        </div>
    </div>
</div>
