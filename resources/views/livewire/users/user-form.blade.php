<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
            <h2 class="text-2xl font-bold mb-6 text-center text-zinc-800 dark:text-white">
                {{ $user ? 'Edit User' : 'Add User' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5" x-data="{ selectedRoles: @entangle('selectedRoles') }">
                <!-- Name -->
                <flux:input wire:model.defer="name" type="name" label="Name" />
                {{-- <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                    <input wire:model.defer="name" id="name" type="text"
                        class="w-full px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}

                <!-- Email -->
                <flux:input wire:model.defer="email" type="email" label="Email" />
                {{-- <div>
                    <label for="email"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                    <input wire:model.defer="email" id="email" type="email"
                        class="w-full px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}
                <!-- Show password fields only on create -->
                @if (!$user)
                    <flux:input wire:model.defer="password" type="password" label="Password" />

                    <flux:input wire:model.defer="password_confirmation" type="password" label="Confirm Password" />
                @endif




                <!-- Roles Selection - Pills -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Roles</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($roles as $name => $label)
                            <button type="button"
                                class="px-3 py-1 rounded-full text-sm border dark:border-zinc-600 transition-all duration-150 ease-in-out"
                                :class="selectedRoles.includes('{{ $name }}') ?
                                    'bg-blue-600 text-white' :
                                    'bg-gray-200 text-gray-800 dark:bg-zinc-700 dark:text-white'"
                                @click="selectedRoles.includes('{{ $name }}')
                        ? selectedRoles.splice(selectedRoles.indexOf('{{ $name }}'), 1)
                        : selectedRoles.push('{{ $name }}')">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    @error('selectedRoles')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Assignment -->
                <div>
                    <label for="assigned_room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Assign Room
                    </label>
                    {{-- && !selectedRoles.includes('chairman') --}}
                    <select wire:model.defer="assigned_room_id" id="assigned_room_id"
                        :disabled="!selectedRoles.includes('lab_incharge')"
                        class="w-full px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white disabled:opacity-50">
                        <option value="">No room assigned</option>
                        @foreach ($roomOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_room_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Submit Button -->
                <div class="flex justify-end mt-4">
                    <button type="button" wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-sm dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        {{ $user ? 'Update' : 'Add' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
