@php
    use App\Support\StatusConfig;
    $statusColors = StatusConfig::statuses();
@endphp

<div class="space-y-6">
    <!-- Heading -->
    <livewire:dashboard-heading title="Computer Units" subtitle="Manage and monitor all Computer units"
        icon="computer-desktop" gradient-from-color="#3b82f6" gradient-to-color="#7c3aed" icon-color="text-blue-500" />

    <!-- Top Bar (Add + Filters + Search) -->
    <div class="flex flex-col lg:flex-row justify-between gap-6">
        <!-- Left: Legends / Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full lg:w-auto">
            <div class="flex items-center justify-between p-4 bg-green-100 dark:bg-green-800/40 rounded-xl shadow">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Operational</span>
                    <span class="text-lg font-bold text-green-700 dark:text-green-300">3</span>
                </div>

            </div>

            <div class="flex items-center justify-between p-4 bg-red-100 dark:bg-red-800/40 rounded-xl shadow">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Non-Operational</span>
                    <span class="text-lg font-bold text-red-700 dark:text-red-300">2</span>
                </div>

            </div>
        </div>

        <!-- Right: Search + Filters + Button -->
        <div class="flex flex-col sm:flex-row flex-wrap items-center gap-3 w-full lg:w-auto">
            <!-- Search -->
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live="search" placeholder="Search units..."
                    class="w-full px-3 py-2 pl-9 border rounded-lg text-sm shadow-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                       dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-200" />
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1111.5 4.5a7.5 7.5 0 015.15 12.15z" />
                </svg>
            </div>

            <!-- Room Filter -->
            <select wire:model.live="roomFilter"
                class="px-3 py-2 border rounded-lg text-sm shadow-sm
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-200">
                <option value="">All Rooms</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select wire:model.live="statusFilter"
                class="px-3 py-2 border rounded-lg text-sm shadow-sm
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-200">
                <option value="">All Status</option>
                @foreach (array_keys($statusColors) as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>

            <!-- Add Unit Button -->
            <flux:button variant="primary" color="green" wire:click="create" class="w-full sm:w-auto">
                + Add Unit
            </flux:button>
        </div>
    </div>


    <!-- Units Table Card -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-200 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    {{-- <th class="px-6 py-4 text-left font-semibold">#</th> --}}
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3 text-center">Room</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                @forelse($units as $unit)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50 odd:bg-white even:bg-gray-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
                        {{-- <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">{{ $unit->id }}</td> --}}
                        <td class="px-4 py-3 font-medium text-zinc-800 dark:text-white">{{ $unit->name }}</td>
                        <td class="px-6 py-4 text-center">{{ $unit->room?->name ?? 'N/A' }}</td>

                        <!-- Status Badge -->
                        <td class="px-4 py-3 text-center">
                            <span
                                class="px-3 py-1 text-xs rounded-full font-semibold 
                                       {{ $statusColors[$unit->status] ?? 'bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-200' }}">
                                {{ $unit->status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        {{-- <td class="px-6 py-4 text-right space-x-4">
                            <button wire:click="openAssignModal({{ $unit->id }})"
                                class="text-blue-500 hover:text-blue-600 text-sm font-medium">Assign</button>
                            <button wire:click="edit({{ $unit->id }})"
                                class="text-yellow-500 hover:text-yellow-600 text-sm font-medium">Edit</button>
                            <button wire:click="delete({{ $unit->id }})"
                                class="text-red-500 hover:text-red-600 text-sm font-medium">Delete</button>
                        </td> --}}

                        <td class="px-4 py-3 text-center space-x-2">
                            <!-- Actions -->
                            <div x-data="{ open: false }" class="relative inline-flex w-full sm:w-auto">
                                <!-- View -->
                                <button wire:click="view({{ $unit->id }})"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs md:text-sm font-medium border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-l-md flex-1 sm:flex-none">
                                    <flux:icon.eye />
                                </button>
                                <!-- Dropdown -->
                                <button @click="open = !open" x-ref="toggleBtn" type="button"
                                    class="inline-flex items-center justify-center px-2 py-2 border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-r-md border-l-0 flex-1 sm:flex-none">
                                    <svg class="h-4 w-4 md:h-5 md:w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <template x-teleport="body">
                                    <div x-show="open" x-transition @click.away="open = false" x-cloak
                                        x-init="$watch('open', value => {
                                            if (value) {
                                                let btn = $refs.toggleBtn.getBoundingClientRect();
                                                $el.style.position = 'absolute';
                                                $el.style.top = (btn.bottom + window.scrollY) + 'px';
                                                $el.style.left = (btn.left + window.scrollX) + 'px';
                                            }
                                        })"
                                        class="z-50 mt-1 w-30 rounded-md shadow-lg bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <button wire:click="openAssignModal({{ $unit->id }})"
                                                @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                Assign
                                            </button>

                                            <button wire:click="edit({{ $unit->id }})" @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.pencil class="h-4 w-4" />
                                                <span>Edit</span>
                                            </button>

                                            <button wire:click="delete({{ $unit->id }})" @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-700">
                                                <flux:icon.trash class="h-4 w-4" />
                                                <span>Delete</span>
                                            </button>

                                        </div>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400 font-medium text-sm">
                            No Units Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modals -->
    @if ($showModal)
        <livewire:system-units.unit-form :unitId="$selectedUnit?->id" :mode="$modalMode" />
    @endif

    @if ($showAssignModal && $assignUnitId)
        <livewire:system-units.unit-assign-parts :unitId="$assignUnitId" />
    @endif

    @if ($modalMode === 'view' && $unitId)
        <livewire:system-units.unit-view :unitId="$unitId" wire:key="unit-view-{{ $unitId }}" />
    @endif

</div>
