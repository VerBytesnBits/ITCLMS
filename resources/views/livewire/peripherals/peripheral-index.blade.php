<div class="p-4 space-y-6">
    <!-- Header -->
    <livewire:dashboard-heading title="Peripherals" subtitle="Manage all peripheral parts" icon="cube"
        gradient-from-color="#ef4444" gradient-to-color="#e0812d" icon-color="text-red-500" />

    <div class="flex justify-end items-center mb-6">
        <flux:button variant="primary" color="blue" wire:click="openCreateModal"> + Add Peripheral</flux:button>
    </div>

    <!-- Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Serial Number</th>
                    <th class="px-4 py-3">Brand</th>
                    <th class="px-4 py-3">Model</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Condition</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Room</th>
                    <th class="px-4 py-3">System Unit</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($peripherals as $peripheral)
                    <tr class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                        <td class="px-4 py-3">{{ $peripheral->id }}</td>
                        <td class="px-4 py-3">{{ $peripheral->serial_number }}</td>
                        <td class="px-4 py-3">{{ $peripheral->brand }}</td>
                        <td class="px-4 py-3">{{ $peripheral->model }}</td>
                        <td class="px-4 py-3">{{ $peripheral->type }}</td>
                        <td class="px-4 py-3">{{ $peripheral->condition }}</td>
                        <td class="px-4 py-3">{{ $peripheral->status }}</td>
                        <td class="px-4 py-3">{{ optional($peripheral->room)->name }}</td>
                        <td class="px-4 py-3">{{ optional($peripheral->systemUnit)->name }}</td>
                        <td class="px-4 py-3 text-center space-x-2">

                            <button wire:click="openEditModal({{ $peripheral->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg shadow text-xs transition">
                                Edit
                            </button>
                            <button wire:click="deletePeripheral({{ $peripheral->id }})"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg shadow text-xs transition">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-6 text-gray-500">No peripherals found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($modal)
        <livewire:peripherals.peripheral-form :id="$id" :mode="$modal" />
    @endif

</div>
