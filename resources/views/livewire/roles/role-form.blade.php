<div>
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 dark:bg-black/60 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-xl w-[400px]">
                <h2 class="text-xl font-semibold mb-4 text-zinc-800 dark:text-white">
                    {{ $roleId ? 'Edit Role' : 'Create Role' }}
                </h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label for="roleName" class="block mb-1 text-sm font-medium text-zinc-700 dark:text-zinc-300">Role Name</label>
                        <input type="text" wire:model.defer="roleName" id="roleName"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" />
                        @error('roleName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <flux:checkbox.group wire:model="permissions" label="permissions">
                        @foreach ($allPermissions as $permission)
                            <flux:checkbox label="{{ $permission->name }}" value="{{ $permission->name }}" />
                        @endforeach
                    </flux:checkbox.group>


                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button" wire:click="$dispatch('closeModal')"
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
