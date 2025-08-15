<?php
use App\Support\PartsConfig;
?>

<div class="p-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        {{-- Search Input --}}
        <div class="order-3 md:order-1 w-full md:w-auto max-w-xs">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search components/peripherals..."
                class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        {{-- Buttons Container --}}
        <div class="flex flex-col md:flex-row gap-4 order-1 md:order-2 w-full md:w-auto max-w-xs">
            {{-- <button wire:click="openSelectComponentsModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow w-full md:w-auto">
                Print / Export PDF
            </button> --}}

            {{-- <button wire:click="openCreateModal"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow w-full md:w-auto">
                Add Unit
            </button> --}}

            <livewire:unit-export-pdf :rooms="$rooms" />
            <button wire:click="openCreateModal"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                Add Unit
            </button>


        </div>
    </div>

    {{-- Legends and Filters --}}
    <div class="flex flex-col md:flex-row justify-between gap-6 text-sm mb-6 px-2 items-center">
        <div class="flex flex-wrap items-center gap-4">
            <span class="w-3 h-3 bg-green-500 rounded-full inline-block"></span> Operational: 5
            <span class="w-3 h-3 bg-red-500 rounded-full inline-block"></span> Non-operational: 10
            <span class="w-3 h-3 bg-yellow-500 rounded-full inline-block"></span> Needs Repair: 10
        </div>

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            {{-- Status Filter --}}
            <select wire:model="filterStatus"
                class="border border-gray-300 rounded-md px-3 py-2 max-w-xs w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="Operational">Operational</option>
                <option value="Non-Operational">Non-Operational</option>
                <option value="Needs Repair">Needs Repair</option>
            </select>

            {{-- Type Filter --}}
            <select wire:model="filterType"
                class="border border-gray-300 rounded-md px-3 py-2 max-w-xs w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Types</option>
                <option value="component">Component</option>
                <option value="peripheral">Peripheral</option>
            </select>

            {{-- Room Filter --}}
            <select wire:model="filterRoom"
                class="border border-gray-300 rounded-md px-3 py-2 max-w-xs w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Rooms</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
    </div>




    {{-- Table --}}
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm mt-6">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            @php
                // Merge both component and peripheral types into one ordered array for display
                $tableColumns = array_merge(PartsConfig::componentTypes(), PartsConfig::peripheralTypes());

                $labels = PartsConfig::typeLabels();
            @endphp

            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase text-sm">
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">ID</div>
                    </th>

                    @foreach ($tableColumns as $type)
                        <th class="px-4 py-3 text-center">
                            <div class="font-bold">{{ strtoupper($labels[$type] ?? ucfirst($type)) }}</div>
                            {{-- Optional: subtitles per type --}}
                            <div class="text-xs text-gray-500">
                                @if ($type === 'memory')
                                    (type & capacity)
                                @elseif(str_contains($type, 'Ssd') || $type === 'hardDiskDrive')
                                    (type & capacity)
                                @elseif($type === 'processor' || $type === 'motherboard' || $type === 'computerCase' || $type === 'monitor')
                                    (model)
                                @endif
                            </div>
                        </th>
                    @endforeach

                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">STATUS</div>
                    </th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($units as $unit)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-4 py-2 text-center">{{ $unit->id }}</td>

                        {{-- Loop through every column dynamically --}}
                        @foreach ($tableColumns as $type)
                            <td class="px-4 py-2 text-center">
                                @php $relation = $unit->$type ?? null; @endphp

                                @if ($relation instanceof \Illuminate\Support\Collection)
                                    {{ $relation->isNotEmpty()
                                        ? $relation->map(fn($p) => trim(($p->brand ?? '') . ' ' . ($p->model ?? '')))->join(', ')
                                        : 'N/A' }}
                                @elseif ($relation)
                                    {{-- For drives, optionally show type & capacity --}}
                                    @if (isset($relation->capacity) && isset($relation->type))
                                        {{ $relation->type }} - ({{ $relation->capacity }})
                                    @else
                                        {{ trim(($relation->brand ?? '') . ' ' . ($relation->model ?? '')) }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        @endforeach

                        {{-- Status --}}
                        <td class="px-4 py-2 text-center">
                            <x-status-badge :status="$unit->status" />
                        </td>


                        {{-- Actions --}}
                        <td class="px-3 py-2 text-center">
                            <div class="relative inline-block text-left" x-data="{ open: false, top: 0, left: 0, modal: @entangle('modal') }"
                                x-effect="if (modal) open = false">
                                <div class="inline-flex rounded-md shadow-sm" role="group">
                                    <button type="button" wire:click="openViewModal({{ $unit->id }})"
                                        class="inline-flex items-center px-4 py-1 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-blue-600 hover:bg-blue-50
                             dark:border-zinc-700 dark:bg-zinc-800 dark:text-blue-400 dark:hover:bg-blue-900">
                                        View
                                    </button>
                                    <button type="button"
                                        @click="
                                const rect = $el.getBoundingClientRect();
                                top = rect.bottom + window.scrollY;
                                left = rect.left + window.scrollX;
                                open = !open;
                            "
                                        class="inline-flex items-center px-2 py-1 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50
                             dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-300 dark:hover:bg-zinc-700">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <template x-teleport="body">
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="absolute z-[9999] w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5
                             dark:bg-zinc-900 dark:ring-white/20"
                                        :style="'position:absolute; top:' + top + 'px; left:' + left + 'px;'">
                                        <div class="py-1">
                                            <button wire:click="openEditModal({{ $unit->id }})"
                                                class="block px-4 py-2 text-sm text-green-600 hover:border-b hover:border-green-500 w-full text-left
                                     dark:text-green-400 dark:hover:border-green-300">
                                                Edit
                                            </button>
                                            <button wire:click="deleteUnit({{ $unit->id }})"
                                                class="block px-4 py-2 text-sm text-red-600 hover:border-b hover:border-red-500 w-full text-left
                                     dark:text-red-400 dark:hover:border-red-300">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    {{-- Modals --}}
    @if ($modal === 'create')
        <livewire:system-units.unit-form :key="'create'" />
    @endif

    @if ($modal === 'edit' && $id)
        <livewire:system-units.unit-form :unit-id="$id" :key="'edit-' . $id" />
    @endif

    {{-- @if ($modal === 'manage' && $id)
        <livewire:system-units.system-unit-manage :unit-id="$id" :key="'manage-' . $id" />
    @endif --}}

    @if ($modal === 'view' && $viewUnit)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 overflow-auto">
            <div
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl animate-[fade-in-scale_0.2s_ease-out] relative">
                <button wire:click="closeModal"
                    class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>

                <h2 class="text-2xl font-bold mb-4 dark:text-white">
                    Unit Details: {{ $viewUnit->name }}
                </h2>

                <p><strong>Room:</strong> {{ $viewUnit->room?->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $viewUnit->status }}</p>

                <h3 class="mt-6 text-xl font-semibold dark:text-white">Components & Peripherals</h3>

                @php
                    $componentTypes = PartsConfig::componentTypes();
                    $peripheralTypes = PartsConfig::peripheralTypes();
                    $labels = PartsConfig::typeLabels();

                    $groupedParts = ['Components' => [], 'Peripherals' => []];

                    foreach ($allParts as $part) {
                        foreach ($componentTypes as $type) {
                            if (
                                $viewUnit->$type instanceof \Illuminate\Support\Collection &&
                                $viewUnit->$type->contains($part)
                            ) {
                                $groupedParts['Components'][] = [
                                    'label' => $labels[$type] ?? ucfirst($type),
                                    'part' => $part,
                                ];
                                continue 2;
                            } elseif ($viewUnit->$type === $part) {
                                $groupedParts['Components'][] = [
                                    'label' => $labels[$type] ?? ucfirst($type),
                                    'part' => $part,
                                ];
                                continue 2;
                            }
                        }
                        foreach ($peripheralTypes as $type) {
                            if (
                                $viewUnit->$type instanceof \Illuminate\Support\Collection &&
                                $viewUnit->$type->contains($part)
                            ) {
                                $groupedParts['Peripherals'][] = [
                                    'label' => $labels[$type] ?? ucfirst($type),
                                    'part' => $part,
                                ];
                                continue 2;
                            } elseif ($viewUnit->$type === $part) {
                                $groupedParts['Peripherals'][] = [
                                    'label' => $labels[$type] ?? ucfirst($type),
                                    'part' => $part,
                                ];
                                continue 2;
                            }
                        }
                    }
                @endphp


                @if ($allParts->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">No components or peripherals found.</p>
                @else
                    @foreach (['Components', 'Peripherals'] as $category)
                        @if (!empty($groupedParts[$category]))
                            <h4 class="mt-4 font-semibold dark:text-white">{{ $category }}</h4>
                            <ul class="list-disc list-inside max-h-48 overflow-auto">
                                @foreach ($groupedParts[$category] as $data)
                                    @php $part = $data['part']; @endphp
                                    @if (is_object($part) && isset($part->brand, $part->model, $part->status))
                                        <li>
                                            <strong>{{ $data['label'] }}:</strong>
                                            {{ $part->brand }} {{ $part->model }} -
                                            <span class="text-green-600 font-semibold">{{ $part->status }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                @endif

                <button wire:click="closeModal" class="mt-6 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Close
                </button>
            </div>
        </div>
    @endif
</div>
