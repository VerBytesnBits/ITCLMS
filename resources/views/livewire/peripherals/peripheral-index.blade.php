<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Peripherals</h1>
        <button wire:click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            + Add Peripheral
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3 border">#</th>
                    <th class="px-4 py-3 border">Serial Number</th>
                    <th class="px-4 py-3 border">Brand</th>
                    <th class="px-4 py-3 border">Model</th>
                    <th class="px-4 py-3 border">Type</th>
                    <th class="px-4 py-3 border">Condition</th>
                    <th class="px-4 py-3 border">Status</th>
                    <th class="px-4 py-3 border">Room</th>
                    <th class="px-4 py-3 border">System Unit</th>
                    <th class="px-4 py-3 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($peripherals as $peripheral)
                    <tr class="hover:bg-gray-50 transition">
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
