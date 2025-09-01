<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Component Parts</h1>
        <button wire:click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            + Add Component
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
                    <th class="px-4 py-3 border">Capacity</th>
                    <th class="px-4 py-3 border">Speed</th>
                    <th class="px-4 py-3 border">Type</th>
                    <th class="px-4 py-3 border">Part</th>
                    <th class="px-4 py-3 border">Condition</th>
                    <th class="px-4 py-3 border">Status</th>
                    <th class="px-4 py-3 border">Warranty</th>
                    <th class="px-4 py-3 border">System Unit</th>
                    <th class="px-4 py-3 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($components as $component)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">{{ $component->id }}</td>
                        <td class="px-4 py-3">{{ $component->serial_number }}</td>
                        <td class="px-4 py-3">{{ $component->brand }}</td>
                        <td class="px-4 py-3">{{ $component->model }}</td>
                        <td class="px-4 py-3">{{ $component->capacity }}</td>
                        <td class="px-4 py-3">{{ $component->speed }}</td>
                        <td class="px-4 py-3">{{ $component->type }}</td>
                        <td class="px-4 py-3">{{ $component->part }}</td>
                        <td class="px-4 py-3">{{ $component->condition }}</td>
                        <td class="px-4 py-3">{{ $component->status }}</td>
                        <td class="px-4 py-3">
                            {{ $component->warranty ? \Carbon\Carbon::parse($component->warranty)->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-4 py-3">{{ optional($component->systemUnit)->name }}</td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="openEditModal({{ $component->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg shadow text-xs transition">
                                Edit
                            </button>
                            <button wire:click="deleteComponent({{ $component->id }})"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg shadow text-xs transition">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center py-6 text-gray-500">No components found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if ($modal)
        <livewire:components-part.form :id="$id" :mode="$modal" />
    @endif
</div>
