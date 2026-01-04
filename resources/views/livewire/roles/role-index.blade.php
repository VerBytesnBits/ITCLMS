<div class="space-y-6">
    <livewire:dashboard-heading title="Roles/Permission" subtitle="Manage all roles/permission" icon="link-slash"
        gradient-from-color="#6e2e87" gradient-to-color="#ab2e87" icon-color="text-purple-300" />

    {{-- <div class="flex justify-end mb-4">
        <flux:button wire:click="openCreateModal" icon="circle-plus" variant="primary" color="green"
            class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
            Add Role</flux:button>
    </div> --}}


    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-blue-500 text-xs uppercase text-zinc-100">
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

                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium 
                       {{ match ($role->name) {
                           'chairman' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                           'lab_incharge' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                           'lab_technician' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                           default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                       } }}">
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </span>


                            {{-- <div class="text-xs text-gray-400">#{{ $role->id }}</div> --}}
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


                        <td class="px-6 py-4 text-right">
                            <flux:button wire:click="openEditModal({{ $role->id }})" icon="pencil"
                                variant="primary"
                                class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 
           dark:focus:ring-green-800 shadow-lg shadow-green-500/50 
           dark:shadow-lg dark:shadow-green-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">

                            </flux:button>
                            <flux:button wire:click="confirmDeleteRole({{ $role->id }})" icon="trash" variant="primary"
                                class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
           dark:focus:ring-red-800 shadow-lg shadow-red-500/50 
           dark:shadow-lg dark:shadow-red-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">

                            </flux:button>
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
