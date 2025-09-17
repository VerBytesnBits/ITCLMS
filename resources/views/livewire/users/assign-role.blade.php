<div class="fixed inset-0 flex items-center justify-center z-50 bg-black/50">
    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 w-full max-w-md shadow-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Assign Role
        </h3>

        <form wire:submit.prevent="assignRole" class="space-y-4">
            @foreach($roles as $role)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input 
                        type="radio" 
                        wire:model="selectedRole" 
                        value="{{ $role }}" 
                        class="form-radio text-blue-500 dark:text-blue-400"
                    />
                    <span class="text-gray-700 dark:text-gray-300">{{ ucfirst($role) }}</span>
                </label>
            @endforeach

            @error('selectedRole') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" wire:click="$dispatch('closeModal')" 
                    class="px-4 py-2 bg-gray-300 dark:bg-zinc-700 rounded-full text-sm hover:bg-gray-400 dark:hover:bg-zinc-600 transition">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-500 rounded-full text-white text-sm hover:bg-blue-600 transition">
                    Assign
                </button>
            </div>
        </form>
    </div>
</div>
