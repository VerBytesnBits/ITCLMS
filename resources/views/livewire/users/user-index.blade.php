<div class="space-y-6">

    <livewire:dashboard-heading title="Users" subtitle="Manage all users" icon="user-group" gradient-from-color="#ebbc49"
        gradient-to-color="#ccf662" icon-color="text-yellow-300" />

    <div class="flex justify-end mb-4">
        <flux:button wire:click="openCreateModal" icon="circle-plus" variant="primary" color="green" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
            Add User
        </flux:button>
    </div>

    <!-- Users Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-blue-500 text-xs uppercase">
                <tr class="text-zinc-100 font-semibold">
                    <th class="px-4 py-3 border-b">Name</th>
                    <th class="px-4 py-3 border-b">Email</th>
                    <th class="px-4 py-3 border-b">Roles</th>
                    <th class="px-4 py-3 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-800/50">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
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
                                @empty
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center space-x-2">
                            
                           <flux:button wire:click="openEditModal({{ $user->id }})" icon="pencil"
                                variant="primary"
                                class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 
           dark:focus:ring-green-800 shadow-lg shadow-green-500/50 
           dark:shadow-lg dark:shadow-green-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">

                            </flux:button>
                            <flux:button  icon="trash" variant="primary"
                                class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
           dark:focus:ring-red-800 shadow-lg shadow-red-500/50 
           dark:shadow-lg dark:shadow-red-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">

                            </flux:button>
                     {{-- wire:click="deleteUser({{ $role->id }})" --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Conditional Modals -->
    @if ($modal === 'create' || $modal === 'edit')
        <livewire:users.user-form :user-id="$selectedUserId" :key="'user-form-' . ($selectedUserId ?? 'create')" />
    @endif

    @if ($modal === 'assign-role' && $selectedUserId)
        <livewire:users.assign-role :user-id="$selectedUserId" :key="'assign-role-' . $selectedUserId" />
    @endif

</div>
