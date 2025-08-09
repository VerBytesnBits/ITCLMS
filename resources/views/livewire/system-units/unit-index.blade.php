<div class="p-4">
    {{-- Create Button --}}
    <button wire:click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded">
        Create System Unit
    </button>

    {{-- Table --}}
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm mt-6">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase text-sm">
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">ID</div>
                    </th>
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">CPU</div>
                        <div class="text-xs text-gray-500">(model)</div>
                    </th>
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">MBOARD</div>
                        <div class="text-xs text-gray-500">(model)</div>
                    </th>
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">RAM</div>
                        <div class="text-xs text-gray-500">(type & capacity)</div>
                    </th>
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">DRIVE</div>
                        <div class="text-xs text-gray-500">(type & capacity)</div>
                    </th>
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">CASING</div>
                        <div class="text-xs text-gray-500">(model)</div>
                    </th>
                    <th class="px-4 py-3 text-center">
                        <div class="font-bold">STATUS</div>
                        <div class="text-xs text-gray-500">(Operational, Needs Repair, Non-operational)</div>
                    </th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($units as $unit)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-4 py-2 text-center">{{ $unit->id }}</td>
                        <td class="px-4 py-2 text-center">
                            {{ $unit->processor
                                ? "{$unit->processor->brand} {$unit->processor->model} {$unit->processor->base_clock}GHz" .
                                    ($unit->processor->boost_clock ? " / {$unit->processor->boost_clock}GHz" : '')
                                : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $unit->motherboard ? $unit->motherboard->brand . ' ' . $unit->motherboard->model : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $unit->memory ? $unit->memory->type . ' ' . $unit->memory->capacity . 'GB' : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if ($unit->drive_type === 'm2' && $unit->m2Ssd)
                                M.2 - ({{ $unit->m2Ssd->capacity }} GB)
                            @elseif ($unit->drive_type === 'sata' && $unit->sataSsd)
                                SATA - ({{ $unit->sataSsd->capacity }} GB)
                            @elseif ($unit->drive_type === 'hdd' && $unit->hardDiskDrive)
                                HDD - ({{ $unit->hardDiskDrive->capacity }} GB)
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $unit->computerCase ? $unit->computerCase->brand . ' ' . $unit->computerCase->model : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $unit->status === 'Working' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $unit->status === 'Under Maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $unit->status === 'Decommissioned' ? 'bg-gray-200 text-gray-700' : '' }}">
                                {{ $unit->status }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            <div class="relative inline-block text-left" x-data="{ open: false, top: 0, left: 0, modal: @entangle('modal') }"
                                x-effect="if (modal) open = false">
                                <button
                                    @click="
                const rect = $el.getBoundingClientRect();
                top = rect.bottom + window.scrollY;
                left = rect.left + window.scrollX;
                open = !open;
            "
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Actions
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Teleport dropdown outside table -->
                                <template x-teleport="body">
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="absolute z-[9999] w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                        :style="'position:absolute; top:' + top + 'px; left:' + left + 'px;'">
                                        <div class="py-1">
                                            <button wire:click="openManageModal({{ $unit->id }})"
                                                class="block px-4 py-2 text-sm text-yellow-600 hover:border-b hover:border-yellow-500 cursor-pointer w-full text-left">
                                                Manage
                                            </button>
                                            <button wire:click="openViewModal({{ $unit->id }})"
                                                class="block px-4 py-2 text-sm text-blue-600 hover:border-b hover:border-blue-500 cursor-pointer w-full text-left">
                                                View
                                            </button>
                                            <button wire:click="openEditModal({{ $unit->id }})"
                                                class="block px-4 py-2 text-sm text-green-600 hover:border-b hover:border-green-500 cursor-pointer w-full text-left">
                                                Edit
                                            </button>
                                            <button wire:click="deleteUnit({{ $unit->id }})"
                                                class="block px-4 py-2 text-sm text-red-600 hover:border-b hover:border-red-500 cursor-pointer w-full text-left">
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


    {{-- Create Modal --}}
    @if ($modal === 'create')
        <livewire:system-units.unit-form :key="'create'" />
    @endif

    {{-- Edit Modal --}}
    @if ($modal === 'edit' && $id)
        <livewire:system-units.unit-form :unit-id="$id" :key="'edit-' . $id" />
    @endif

    {{-- Manage Modal --}}
    @if ($modal === 'manage' && $id)
        <livewire:system-units.system-unit-manage :unit-id="$id" :key="'manage-' . $id" />
    @endif

    {{-- View Modal --}}
    @if ($modal === 'view' && $viewUnit)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 overflow-auto">
            <div
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl animate-[fade-in-scale_0.2s_ease-out] relative">
                <button wire:click="closeModal"
                    class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>

                <h2 class="text-2xl font-bold mb-4 dark:text-white">
                    System Unit Details: {{ $viewUnit->name }}
                </h2>

                <p><strong>Room:</strong> {{ $viewUnit->room?->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $viewUnit->status }}</p>

                <h3 class="mt-6 text-xl font-semibold dark:text-white">Components & Peripherals</h3>

                @php
                    $componentsTypes = [
                        'processor',
                        'cpuCooler',
                        'motherboard',
                        'memory',
                        'graphicsCard',
                        'm2Ssd',
                        'sataSsd',
                        'hardDiskDrive',
                        'powerSupply',
                        'computerCase',
                    ];
                    $peripheralsTypes = ['monitor', 'keyboard', 'mouse', 'headset', 'speaker', 'webCamera'];
                    $groupedParts = ['Components' => [], 'Peripherals' => []];

                    foreach ($allParts as $part) {
                        foreach ($componentsTypes as $type) {
                            if (
                                $viewUnit->$type instanceof \Illuminate\Support\Collection &&
                                $viewUnit->$type->contains($part)
                            ) {
                                $groupedParts['Components'][] = $part;
                                continue 2;
                            } elseif ($viewUnit->$type === $part) {
                                $groupedParts['Components'][] = $part;
                                continue 2;
                            }
                        }
                        foreach ($peripheralsTypes as $type) {
                            if (
                                $viewUnit->$type instanceof \Illuminate\Support\Collection &&
                                $viewUnit->$type->contains($part)
                            ) {
                                $groupedParts['Peripherals'][] = $part;
                                continue 2;
                            } elseif ($viewUnit->$type === $part) {
                                $groupedParts['Peripherals'][] = $part;
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
                                @foreach ($groupedParts[$category] as $part)
                                    @if (is_object($part) && isset($part->brand, $part->model, $part->status))
                                        <li>{{ $part->brand }} {{ $part->model }} - <span
                                                class="text-green-600 font-semibold">{{ $part->status }}</span></li>
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
