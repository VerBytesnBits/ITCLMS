<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden
                   border border-zinc-200 dark:border-zinc-700 animate-[fade-in-scale_0.2s_ease-out]">

            <!-- Header -->
            <div
                class="px-6 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700
                       text-white border-b border-blue-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <flux:icon.layout-grid class="w-5 h-5" />
                    {{ $roomId ? 'Update Room' : 'Add Room' }}
                </h2>
                <button wire:click="$dispatch('closeModal')" class="p-2 rounded-full hover:bg-red-500 transition">
                    <flux:icon.x class="w-5 h-5" />
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-5">
                <!-- Room Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Room Name
                    </label>
                    <input wire:model.defer="name" id="name" type="text"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-zinc-700 dark:text-white 
                               focus:ring-blue-500 focus:border-blue-500" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Status
                    </label>
                    <select wire:model="status" id="status"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-zinc-700 dark:text-white 
                               focus:ring-blue-500 focus:border-blue-500">
                        <option value="Available">Available</option>
                        <option value="occupied">Unavailable</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div
                class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 
                       bg-zinc-50 dark:bg-zinc-900/50 flex justify-end gap-2">
                <button type="button" wire:click="$dispatch('closeModal')"
                    class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 hover:bg-gray-300 
                           dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-100 transition">
                    Cancel
                </button>
                <button type="submit" wire:click="save"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-white 
                           bg-blue-600 hover:bg-blue-700 transition shadow">
                    {{ $roomId ? 'Update' : 'Add' }}
                </button>
            </div>

        </div>
    </div>
</div>
