<div class="p-4 space-y-6">
    <!-- Header -->
    <livewire:dashboard-heading title="Maintenance" subtitle="Manage all maintenance records" icon="wrench"
        gradient-from-color="#ab2e87" gradient-to-color="#c62c5f" icon-color="text-pink-500" />



    {{-- Add Maintenance Button --}}
    <div class="flex justify-end mb-4">
        <button wire:click="openModal" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Maintenance
        </button>
    </div>

    {{-- Maintenance Table --}}
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-4 py-2">Unit</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Created By</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($maintenances as $maintenance)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $maintenance->unit->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $maintenance->type }}</td>
                        <td class="px-4 py-2">{{ $maintenance->description ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span
                                class="px-2 py-1 rounded 
                                @if ($maintenance->status === 'Pending') bg-yellow-200 text-yellow-800
                                @elseif($maintenance->status === 'In Progress') bg-blue-200 text-blue-800
                                @else bg-green-200 text-green-800 @endif">
                                {{ $maintenance->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $maintenance->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            @if ($maintenance->status === 'Pending')
                                <button wire:click="startMaintenance({{ $maintenance->id }})"
                                    class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                    Start
                                </button>
                            @elseif($maintenance->status === 'In Progress')
                                <button wire:click="completeMaintenance({{ $maintenance->id }})"
                                    class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                    Complete
                                </button>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">No maintenance records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 px-4">
            <div class="bg-white p-6 rounded shadow w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Add Maintenance</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Unit</label>
                        <select wire:model="selectedUnit" class="w-full border rounded px-2 py-1">
                            <option value="">-- Select Unit --</option>
                            @foreach ($systemUnits as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedUnit')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Type</label>
                        <input type="text" wire:model.defer="type" class="w-full border rounded px-2 py-1">
                        @error('type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea wire:model.defer="description" class="w-full border rounded px-2 py-1"></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select wire:model.defer="status" class="w-full border rounded px-2 py-1">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                        @error('status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                </div>
            </div>
        </div>
    @endif
</div>
