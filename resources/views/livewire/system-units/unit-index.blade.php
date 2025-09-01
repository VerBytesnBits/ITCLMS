<div class="p-4 space-y-6">


    <livewire:dashboard-heading title="Computer Units" subtitle="Manage all units" icon="computer-desktop"
        gradient-from-color="#3b82f6" gradient-to-color="#7c3aed" icon-color="text-blue-500" />



    <!-- Add Unit Button -->
    <div class="flex justify-end">
        <button wire:click="create"
            class="bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-600 dark:hover:bg-blue-700 transition shadow">
            + Add Unit
        </button>
    </div>

    <!-- Units Table Card -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Room</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Condition</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $unit)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                        <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">{{ $unit->id }}</td>
                        <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">{{ $unit->name }}</td>
                        <td class="px-6 py-4">{{ $unit->room?->name ?? 'N/A' }}</td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'Available' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'Under Maintenance' =>
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'Defective' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'Operational' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                ];
                            @endphp
                            <span
                                class="px-2 py-1 text-xs rounded-full font-semibold {{ $statusColors[$unit->status] ?? 'bg-gray-100 dark:bg-gray-800 dark:text-gray-200 text-gray-800' }}">
                                {{ $unit->status }}
                            </span>
                        </td>

                        <!-- Condition Badge -->
                        <td class="px-6 py-4">
                            @php
                                $conditionColors = [
                                    'Operational' =>
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',

                                    'defective' =>
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'Non-operational' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                ];
                            @endphp
                            <span
                                class="px-2 py-1 text-xs rounded-full font-semibold {{ $conditionColors[$unit->condition] ?? 'bg-gray-100 dark:bg-gray-800 dark:text-gray-200 text-gray-800' }}">
                                {{ $unit->condition }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right space-x-3">
                            <button wire:click="openAssignModal({{ $unit->id }})"
                                class="text-blue-500 text-sm font-medium hover:underline cursor-pointer">Assign</button>
                            <button wire:click="edit({{ $unit->id }})"
                                class="text-yellow-500 text-sm font-medium hover:underline cursor-pointer">Edit</button>
                            <button wire:click="delete({{ $unit->id }})"
                                class="text-red-500 text-sm font-medium hover:underline cursor-pointer">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-400 font-medium">No Units Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modals -->
    @if ($showModal)
        <livewire:system-units.unit-form :unit-id="$selectedUnit?->id" :mode="$modalMode" />
    @endif

    @if ($showAssignModal && $assignUnitId)
        <livewire:system-units.unit-assign-parts :unitId="$assignUnitId" />
    @endif
</div>
