<div>
    @if($showModal)
        <div class="fixed inset-0 bg-black/40 dark:bg-black/60 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-xl w-[400px]">
                <h2 class="text-xl font-semibold mb-4 text-zinc-800 dark:text-white">Create User</h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Name</label>
                        <input type="text" wire:model.defer="name" class="w-full border px-3 py-2 rounded dark:bg-zinc-900 dark:text-white dark:border-zinc-700" />
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                        <input type="email" wire:model.defer="email" class="w-full border px-3 py-2 rounded dark:bg-zinc-900 dark:text-white dark:border-zinc-700" />
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Password</label>
                        <input type="password" wire:model.defer="password" class="w-full border px-3 py-2 rounded dark:bg-zinc-900 dark:text-white dark:border-zinc-700" />
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Confirm Password</label>
                        <input type="password" wire:model.defer="password_confirmation" class="w-full border px-3 py-2 rounded dark:bg-zinc-900 dark:text-white dark:border-zinc-700" />
                        @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 dark:bg-zinc-700 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-zinc-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
