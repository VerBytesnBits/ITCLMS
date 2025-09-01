<div>

    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
            <h2 class="text-xl font-semibold mb-4">
                {{ $mode === 'create' ? 'Add  Unit' : 'Edit Unit' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-4">
                <!-- Unit Name -->
                <div>
                    <label class="block text-sm font-medium">Unit Name</label>
                    <input type="text" wire:model.defer="name" class="w-full rounded border-gray-300"
                        placeholder="Enter unit name">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Serial Number -->
                {{-- <div>
                    <label class="block text-sm font-medium">Serial Number</label>
                    <input type="text" wire:model.defer="serial_number" class="w-full rounded border-gray-300"
                        placeholder="Optional">
                    @error('serial_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select wire:model.defer="status" class="w-full rounded border-gray-300">
                        <option value="Available">Available</option>
                        <option value="Operational">Operational</option>
                        <option value="Under Maintenance">Under Maintenance</option>
                        <option value="Defective">Defective</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Room -->
                <div>
                    <label class="block text-sm font-medium">Assigned Room</label>
                    <select wire:model.defer="room_id" class="w-full rounded border-gray-300">
                        <option value="">-- Select Room --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                        wire:click="$dispatch('closeModal')">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ $mode === 'create' ? 'Save Unit' : 'Update Unit' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
