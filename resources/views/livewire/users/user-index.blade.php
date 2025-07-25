<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage all users') }}</flux:subheading>
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
                User</button>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-2">{{ $user->id }}</td>
                                <td class="px-6 py-2">{{ $user->name }}</td>
                                <td class="px-6 py-2">{{ $user->email }}</td>


                                <td class="px-6 py-2">
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full {{ \App\Services\RoleColorService::get($role->name) }}">
                                            {{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}
                                        </span>
                                    @endforeach
                                </td>


                                <td class="px-6 py-2">
                                    <button wire:click="openEditModal({{ $user->id }})"
                                        class="text-blue-500 px-2">Edit</button>
                                    <button wire:click="deleteUser({{ $user->id }})"
                                        class="text-red-500 px-2">Delete</button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Modal --}}
            @if ($modal === 'create' || $modal === 'edit')
                <livewire:users.user-form :user-id="$id" />
            @endif
        </div>
    </div>

</div>
