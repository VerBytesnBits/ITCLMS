<div class="p-4 space-y-6">
    <!-- Header -->
    <livewire:dashboard-heading title="Components" subtitle="Manage all component parts" icon="cpu-chip"
        gradient-from-color="#10b981" gradient-to-color="#047857" icon-color="text-green-600" />



    <div class="flex justify-end mb-4">
        <flux:button variant="primary" color="blue" wire:click="openCreateModal"> + Add Component</flux:button>
    </div>

    <!-- Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Serial Number</th>
                    <th class="px-4 py-3">Brand</th>
                    <th class="px-4 py-3">Model</th>
                    <th class="px-4 py-3">Part</th>
                    <th class="px-4 py-3">Condition</th>
                    <th class="px-4 py-3">Status</th>
                    {{-- <th class="px-4 py-3">Warranty</th> --}}

                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($components as $component)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                        <td class="px-4 py-3">{{ $component->id }}</td>
                        <td class="px-4 py-3">{{ optional($component->systemUnit)->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $component->serial_number }}</td>
                        <td class="px-4 py-3">{{ $component->brand }}</td>
                        <td class="px-4 py-3">{{ $component->model }}</td>
                        <td class="px-4 py-3">{{ $component->part }}</td>
                        <td class="px-4 py-3">
                            @php
                                $conditionColors = [
                                    'Excellent' => 'bg-green-100 text-green-700',
                                    'Good' => 'bg-blue-100 text-blue-700',
                                    'Fair' => 'bg-yellow-100 text-yellow-700',
                                    'Poor' => 'bg-red-100 text-red-700',
                                ];
                            @endphp

                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $conditionColors[$component->condition] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $component->condition }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'Available' => 'bg-green-100 text-green-700',
                                    'Operational' => 'bg-blue-100 text-blue-700',
                                    'Under Maintenance' => 'bg-yellow-100 text-yellow-700',
                                    'Defective' => 'bg-red-100 text-red-700',
                                ];
                            @endphp

                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$component->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $component->status }}
                            </span>
                        </td>

                        {{-- <td class="px-4 py-3">
                            {{ $component->warranty ? \Carbon\Carbon::parse($component->warranty)->format('M d, Y') : '—' }}
                        </td> --}}

                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="openViewModal({{ $component->id }})"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg shadow text-xs transition">
                                View
                            </button>

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

    @if ($modal === 'create')
        <livewire:components-part.form :id="$id" :mode="$modal" />
    @endif

    @if ($modal === 'edit')
        <livewire:components-part.form :id="$id" :mode="$modal" />
    @endif

    @if ($modal === 'view')
        <livewire:components-part.view :id="$id" />
    @endif

</div>
