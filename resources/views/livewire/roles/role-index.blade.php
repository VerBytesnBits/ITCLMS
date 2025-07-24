<div>
    <div>
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Roles') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage all roles') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        @if (session()->has('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto mt-6">
            <div class="p-4">
                <button wire:click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded">Create
                    Permission</button>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Permissions</th>
                                <th class="px-6 py-3">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-2">{{ $role->id }}</td>
                                    <td class="px-6 py-2">{{ $role->name }}</td>
                                    <td class="px-6 py-2 text-sm text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex gap-2">
                                            @foreach ($role->permissions->chunk(5) as $chunk)
                                                <div class="flex flex-col gap-1">
                                                    @foreach ($chunk as $permission)
                                                        <flux:badge color="lime">{{ $permission->name }}</flux:badge>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>


                                    <td class="px-6 py-2">
                                        <button wire:click="openEditModal({{ $role->id }})"
                                            class="text-blue-500 px-2">Edit</button>
                                        <button wire:click="deleteUser({{ $role->id }})"
                                            class="text-red-500 px-2">Delete</button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Modal --}}
                @if ($modal === 'create' || $modal === 'edit')
                    <livewire:roles.role-form :role-id="$id" />
                @endif
            </div>
        </div>

    </div>
