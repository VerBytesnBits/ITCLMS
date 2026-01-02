<flux:modal name="user-modal" class="md:w-[32rem]">
    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Header --}}
        <div>
            <flux:heading class="font-bold text-center" size="lg">
                {{ $user ? 'Update User' : 'Create User' }}
            </flux:heading>
            <flux:text class="mt-2 text-center">
                Fill in the user information below.
            </flux:text>
        </div>

        {{-- Name --}}
        <div class="form-group">
            <flux:input
                wire:model.defer="name"
                label="Name"
                placeholder="Enter full name"
            />
        </div>

        {{-- Email --}}
        <div class="form-group">
            <flux:input
                wire:model.defer="email"
                type="email"
                label="Email"
                placeholder="user@example.com"
            />
        </div>

        {{-- Password --}}
        <div class="form-group">
            <flux:input
                wire:model.defer="password"
                type="password"
                label="Password"
                placeholder="••••••••"
            />
        </div>

        {{-- Roles --}}
        <div class="form-group">
            <label class="block text-sm font-medium mb-2">
                Roles
            </label>

            <div class="flex flex-wrap gap-2">
                @foreach ($roles as $name => $label)
                    <button
                        type="button"
                        wire:click="$set('selectedRole', '{{ $name }}')"
                        class="px-4 py-1 rounded-full text-sm font-medium border transition
                        {{ $selectedRole === $name
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'bg-gray-200 text-gray-800 dark:bg-zinc-700 dark:text-white dark:border-zinc-600'
                        }}"
                        @if ($user && $user->hasRole('chairman')) disabled @endif
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end pt-4">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost">
                    Cancel
                </flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="primary" class="ms-2">
                {{ $user ? 'Update' : 'Save' }}
            </flux:button>
        </div>
    </form>
</flux:modal>
