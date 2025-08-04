<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
            <h2 class="text-2xl font-bold mb-6 text-center text-zinc-800 dark:text-white">
                {{ $user ? 'Update User' : 'Create User' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                    <input type="text" wire:model.defer="name" placeholder="Full name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600 focus:ring-2 focus:ring-blue-500" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                    <input type="email" wire:model.defer="email" placeholder="user@example.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600 focus:ring-2 focus:ring-blue-500" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                    <input type="password" wire:model.defer="password" placeholder="••••••••"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600 focus:ring-2 focus:ring-blue-500" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Role -->
                <div>
                    <label for="role"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Role</label>
                    <select wire:model.defer="role" id="role"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600 focus:ring-2 focus:ring-blue-500">
                        <option value="">Select role</option>
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-sm dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm cursor-pointer">
                        {{ $user ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
