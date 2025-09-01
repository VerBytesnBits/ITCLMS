<div class="p-4 space-y-6">
    <livewire:dashboard-heading title="Roles/Permission" subtitle="Manage all roles/permission" icon="link-slash"
        gradient-from-color="#6e2e87" gradient-to-color="#ab2e87" icon-color="text-purple-300" />

    <div class="flex justify-end mb-4">
        <button wire:click="openCreateModal"
            class="bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-600 dark:hover:bg-blue-700 transition shadow">
            Create Role
        </button>
    </div>

    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Permissions</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                        <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">
                            {{ $role->name }}
                            <div class="text-xs text-gray-400">#{{ $role->id }}</div>
                        </td>

                        <td class="px-6 py-4" x-data="{ showAll: false }">
                            <div class="flex flex-wrap gap-1">
                                <template x-if="!showAll">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($role->permissions->take(5) as $permission)
                                            <span
                                                class="px-2 py-1 text-xs bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-200 rounded-full">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach

                                        @if ($role->permissions->count() > 5)
                                            <button @click="showAll = true"
                                                class="px-2 py-1 text-xs bg-gray-200 text-gray-600 dark:bg-zinc-700 dark:text-gray-300 rounded-full hover:underline cursor-pointer">
                                                +{{ $role->permissions->count() - 2 }} more
                                            </button>
                                        @endif
                                    </div>
                                </template>

                                <template x-if="showAll">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($role->permissions as $permission)
                                            <span
                                                class="px-2 py-1 text-xs bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-200 rounded-full">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach

                                        <button @click="showAll = false"
                                            class="px-2 py-1 text-xs text-blue-600 dark:text-blue-400 hover:underline ml-2 cursor-pointer">
                                            Show Less
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </td>


                        <td class="px-6 py-4 text-right space-x-3">
                            <button wire:click="openEditModal({{ $role->id }})"
                                class="text-blue-500 text-sm font-medium hover:underline cursor-pointer">
                                Edit
                            </button>

                            <button wire:click="deleteUser({{ $role->id }})"
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
        <livewire:roles.role-form :role-id="$id" />
    @endif
</div>
