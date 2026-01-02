<div>
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
            <h2 class="text-2xl font-semibold mb-6 text-zinc-800 dark:text-white text-center">
                {{ $roleId ? 'Edit Role' : 'Create Role' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <!-- Role Name -->
                <div>
                    <label for="roleName" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Role Name
                    </label>
                    <flux:input  wire:model.defer="roleName" id="roleName" placeholder="e.g. Lab Technician" readonly/>
                    @error('roleName')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $groupedPermissions = $allPermissions->groupBy(function ($permission) {
                        return explode('.', $permission->name)[0]; // prefix before dot
                    });
                @endphp

                <div class="max-h-[60vh] overflow-y-auto pr-2 space-y-6 mt-1">
                    @foreach ($groupedPermissions as $group => $permissions)
                        <div>
                            <h4 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 capitalize mb-2">
                                {{ $group }}
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($permissions as $permission)
                                    <flux:checkbox label="{{ str_replace($group . '.', '', $permission->name) }}"
                                        value="{{ $permission->name }}" wire:model="permissions" class="w-full" />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>



                <!-- Actions -->
                <div class="flex justify-end pt-4 space-x-2">
                    <button type="button" wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 rounded-lg bg-gray-300 text-zinc-800 hover:bg-gray-400 dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition cursor-pointer">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
