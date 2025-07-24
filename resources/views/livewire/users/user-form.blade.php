<div>
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 dark:text-white">
                {{ $user ? 'Update User' : 'Create User' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Name</label>
                    <input type="text" wire:model.defer="name"
                        class="w-full border px-3 py-2 rounded dark:bg-zinc-700 dark:text-white" />
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Email</label>
                    <input type="email" wire:model.defer="email"
                        class="w-full border px-3 py-2 rounded dark:bg-zinc-700 dark:text-white" />
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Password</label>
                    <input type="password" wire:model.defer="password"
                        class="w-full border px-3 py-2 rounded dark:bg-zinc-700 dark:text-white" />
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Confirm Password</label>
                    <input type="password" wire:model.defer="password_confirmation"
                        class="w-full border px-3 py-2 rounded dark:bg-zinc-700 dark:text-white" />
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select wire:model="role" id="role"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded">
                        {{ $user ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
