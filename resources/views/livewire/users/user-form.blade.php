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
                <flux:input wire:model.defer="name" label="Name" placeholder="Enter full name" />

                <!-- Email -->
                <flux:input wire:model.defer="email" type="email" label="Email" placeholder="user@example.com" />

                <!-- Password -->
                <flux:input wire:model.defer="password" type="password" label="Password" placeholder="••••••••" />

                <!-- Roles Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Roles
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($roles as $name => $label)
                            <button type="button"
                                class="px-4 py-1 rounded-full text-sm font-medium border transition-all duration-150 ease-in-out
                       hover:scale-105 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500
                       dark:border-zinc-600
                       {{ $selectedRole === $name ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-200 text-gray-800 dark:bg-zinc-700 dark:text-white dark:border-zinc-600' }}"
                                wire:click="$set('selectedRole', '{{ $name }}')"
                                @if ($user && $user->hasRole('chairman')) disabled @endif>
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>


                {{-- <flux:select wire:model.defer="assigned_room_id" id="assigned_room_id" label="Assign Room"
                    placeholder="No room assigned" x-bind:disabled="$wire.selectedRole !== 'lab_incharge'">
                    @foreach ($roomOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </flux:select> --}}



                <!-- Submit + Cancel -->
                <div class="flex justify-end gap-3 mt-4">
                    <flux:button type="button" color="subtle" wire:click="$dispatch('closeModal')">
                        Cancel
                    </flux:button>

                    <flux:button type="submit" variant="primary">
                        {{ $user ? 'Update' : 'Save' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
