<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
            <h2 class="text-2xl font-bold mb-6 text-center text-zinc-800 dark:text-white">
                {{ $roomId ? 'Update Room' : 'Create Room' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <!-- Room Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Room Name
                    </label>
                    <input wire:model.defer="name" id="name" type="text"
                        class="w-full px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Description
                    </label>
                    <textarea wire:model.defer="description" id="description" rows="3"
                        class="w-full px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Status
                    </label>
                    <select wire:model.defer="status" id="status"
                        class="w-full px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit + Cancel -->
                <div class="flex justify-end mt-4">
                    <button type="button" wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-sm dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        {{ $roomId ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
