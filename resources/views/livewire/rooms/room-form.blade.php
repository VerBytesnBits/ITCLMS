<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
            <h2 class="text-2xl font-bold mb-6 text-center text-zinc-800 dark:text-white">
                {{ $room ? 'Edit Room' : 'Add Room' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Room Name</label>
                    <input type="text" wire:model.defer="name" placeholder="Enter room name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md 
                        dark:bg-zinc-700 dark:text-white dark:border-zinc-600 focus:ring-2 focus:ring-blue-500" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lab In-Charge -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Lab In-Charge</label>
                    <select wire:model="lab_in_charge_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md 
                        dark:bg-zinc-700 dark:text-white dark:border-zinc-600 focus:ring-2 focus:ring-blue-500 form-select">


                        <option value="">— Select Lab In-Charge —</option>
                        @foreach ($labInChargeOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach


                    </select>



                    @error('lab_in_charge_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                    <select wire:model="status" class="form-select">
                        <option value="Available">Available</option>
                        <option value="Unavailable">Unavailable</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-sm 
                        dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm cursor-pointer">
                        {{ $room ? 'Update' : 'Add' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
