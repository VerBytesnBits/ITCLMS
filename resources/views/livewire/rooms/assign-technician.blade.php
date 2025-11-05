<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden
                   animate-[fade-in-scale_0.2s_ease-out] border border-zinc-200 dark:border-zinc-700">

            <!-- Header -->
            <div
                class="px-6 py-4 bg-gradient-to-r from-indigo-500 via-indigo-600 to-indigo-700
                       text-white border-b border-indigo-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <flux:icon.wrench class="w-5 h-5" />
                    Assign Technicians to {{ $room->name }}
                </h2>
                <button wire:click="$dispatch('closeModal')" 
                        class="text-white/80 hover:text-white transition">
                    <flux:icon.x class="w-5 h-5" />
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Lab-Technician(s)
                    </label>
                    <select wire:model="selectedTechnicianIds" multiple
                            class="w-full rounded-lg border-gray-300 dark:border-zinc-600 
                                   dark:bg-zinc-700 dark:text-zinc-100 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($technicianOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('selectedTechnicianIds')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div
                class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 
                       bg-zinc-50 dark:bg-zinc-900/50 flex justify-end gap-2">
                <button wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 hover:bg-gray-300 
                               dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-100 transition">
                    Cancel
                </button>
                <button wire:click="save"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-white 
                               bg-indigo-600 hover:bg-indigo-700 transition shadow">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>
