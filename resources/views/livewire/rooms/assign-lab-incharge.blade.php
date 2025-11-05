<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden
                   animate-[fade-in-scale_0.2s_ease-out] border border-zinc-200 dark:border-zinc-700">

            <!-- Header -->
            <div
                class="px-6 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700
                       text-white border-b border-blue-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <flux:icon.user class="w-5 h-5" />
                    Assign Lab In-Charge
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
                        Select Lab In-Charge(s)
                    </label>
                    <select wire:model="user_ids" multiple
                            class="w-full rounded-lg border-gray-300 dark:border-zinc-600 
                                   dark:bg-zinc-700 dark:text-zinc-100 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_ids')
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
                               bg-blue-600 hover:bg-blue-700 transition shadow">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>
