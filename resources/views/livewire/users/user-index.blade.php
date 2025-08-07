<div class="p-4 space-y-6">
    <div class="relative w-full px-4 py-2">
        <flux:heading size="xl" level="1" class="dark:text-white">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6 dark:text-gray-300">{{ __('Manage all users') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex justify-start">
        <button wire:click="openCreateModal"
            class="bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-600 dark:hover:bg-blue-700 transition shadow">
            Create User
        </button>
    </div>

    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow mt-4">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Assigned Room</th>
                    <th class="px-6 py-4">Roles</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                        <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">
                            {{ $user->id }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                            {{ $user->assignedRoom?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($user->roles as $role)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-md {{ \App\Services\RoleColorService::get($role->name) }}">
                                        {{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-400">No roles assigned.</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <button wire:click="openEditModal({{ $user->id }})"
                                class="text-blue-500 text-sm font-medium hover:underline cursor-pointer">
                                Edit
                            </button>
                            <button wire:click="deleteUser({{ $user->id }})"
                                class="text-red-500 text-sm font-medium hover:underline cursor-pointer">
                                Delete
                            </button>
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
