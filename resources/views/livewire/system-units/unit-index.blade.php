<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">System Units</h2>
        <button wire:click="create" class="px-3 py-2 bg-blue-600 text-white rounded-lg">+ Add Unit</button>
    </div>

    <table class="w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 text-left">#</th>
                <th class="px-3 py-2 text-left">Name</th>
                {{-- <th class="px-3 py-2 text-left">Serial</th> --}}
                <th class="px-3 py-2 text-left">Room</th>
                <th class="px-3 py-2 text-left">Status</th>
                <th class="px-3 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $unit)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ $unit->id }}</td>
                    <td class="px-3 py-2">{{ $unit->name }}</td>
                    {{-- <td class="px-3 py-2">{{ $unit->serial_number }}</td> --}}
                    <td class="px-3 py-2">{{ $unit->room?->name }}</td>
                    <td class="px-3 py-2">{{ $unit->status }}</td>
                    <td class="px-3 py-2">
                        <!-- Example Assign button in your unit list -->

                        <button class="px-2 py-1 bg-blue-600 text-white rounded"
                            wire:click="openAssignModal({{ $unit->id }})">
                            Assign
                        </button>




                        <button wire:click="edit({{ $unit->id }})"
                            class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</button>
                        <button wire:click="delete({{ $unit->id }})"
                            class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">No Units Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Modal -->
    @if ($showModal)
        <livewire:system-units.unit-form :unit-id="$selectedUnit?->id" :mode="$modalMode" />
    @endif
    <!-- Assign Modal -->
    @if ($showAssignModal && $assignUnitId)
        <livewire:system-units.unit-assign-parts :unitId="$assignUnitId" />
    @endif

</div>
