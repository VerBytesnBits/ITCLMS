<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div  class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
        <h2 class="text-xl font-bold mb-4">
            {{ $modalMode === 'create' ? 'Create System Unit' : 'Edit System Unit' }}
        </h2>

        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label for="name" class="block mb-1 font-semibold">Name</label>
                <input type="text" id="name" wire:model.defer="name"
                    class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white" />
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="room_id" class="block mb-1 font-semibold">Room</label>
                <select id="room_id" wire:model.defer="room_id"
                    class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
                @error('room_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-1 font-semibold">Status</label>
                <select id="status" wire:model.defer="status"
                    class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                    <option value="Working">Working</option>
                    <option value="Under Maintenance">Under Maintenance</option>
                    <option value="Decommissioned">Decommissioned</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" wire:click="$dispatch('closeModal')"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 dark:bg-zinc-600 dark:hover:bg-zinc-700">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    {{ $unit ? 'Update' : 'save' }}
                </button>
            </div>
        </form>
    </div>
</div>
