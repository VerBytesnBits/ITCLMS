<div class="p-4">
    <button wire:click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded">
        Create System Unit
    </button>

    <table class="table-auto w-full mt-6 border border-gray-300 dark:border-gray-700">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Room</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($units as $unit)
                <tr class="border-b border-gray-300 dark:border-gray-700">
                    <td class="px-4 py-2 border">{{ $unit->id }}</td>
                    <td class="px-4 py-2 border">{{ $unit->name }}</td>
                    <td class="px-4 py-2 border">{{ $unit->room?->name ?? '—' }}</td>
                    <td class="px-4 py-2 border">
                        <span
                            class="px-2 py-1 rounded
                            {{ $unit->status === 'Working' ? 'bg-green-200 text-green-800' : '' }}
                            {{ $unit->status === 'Under Maintenance' ? 'bg-yellow-200 text-yellow-800' : '' }}
                            {{ $unit->status === 'Decommissioned' ? 'bg-gray-300 text-gray-600' : '' }}">
                            {{ $unit->status }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border space-x-2">
                        {{-- <a href="{{ route('system-units.index', $unit->id) }}"
                            class="text-yellow-500 text-sm font-medium hover:underline cursor-pointer">Manage</a> --}}
                        <button wire:click="openManageModal({{ $unit->id }})"
                            class="text-yellow-500 text-sm font-medium hover:underline cursor-pointer">
                            Manage
                        </button>
                        <button wire:click="openViewModal({{ $unit->id }})"
                            class="text-blue-600 hover:underline">View</button>
                        <button wire:click="openEditModal({{ $unit->id }})"
                            class="text-green-600 hover:underline">Edit</button>
                        <button wire:click="deleteUnit({{ $unit->id }})"
                            class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Modals --}}
    @if ($modal === 'create')
        <livewire:system-units.unit-form :key="'create'" />
    @elseif ($modal === 'edit' && $id)
        <livewire:system-units.unit-form :unit-id="$id" :key="'edit-' . $id" />
    @elseif ($modal === 'manage' && $id)
        <livewire:system-units.system-unit-manage :unit-id="$id" :key="'manage-' . $id" />
    @elseif ($modal === 'view' && $viewUnit)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 overflow-auto">
            <div
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-5xl 
        animate-[fade-in-scale_0.2s_ease-out] relative">

                <button wire:click="closeModal"
                    class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white text-2xl">&times;</button>

                <h2 class="text-2xl font-bold mb-4 dark:text-white">
                    System Unit Details: {{ $viewUnit->name }}
                </h2>

                <p><strong>Room:</strong> {{ $viewUnit->room?->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $viewUnit->status }}</p>

                @php
                    $cpu = $viewUnit->processors->first();
                    $mboard = $viewUnit->motherboards->first();
                    $ram = $viewUnit->memories->first();
                    $drive =
                        $viewUnit->hardDiskDrives->first() ??
                        ($viewUnit->sataSsds->first() ?? $viewUnit->m2Ssds->first());
                    $casing = $viewUnit->computerCase?->first();
                    $status = $viewUnit->status;
                @endphp

                <div class="overflow-x-auto mt-6">
                    <table class="min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                        <thead class="bg-gray-100 dark:bg-zinc-700 dark:text-white">
                            <tr>
                                <th class="border px-4 py-2">#</th>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">CPU (model)</th>
                                <th class="border px-4 py-2">MBOARD (model)</th>
                                <th class="border px-4 py-2">RAM (type & capacity)</th>
                                <th class="border px-4 py-2">DRIVE (type & capacity)</th>
                                <th class="border px-4 py-2">CASING (model)</th>
                                <th class="border px-4 py-2">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="dark:text-white">
                            <tr>
                                <td class="border px-4 py-2">1</td>
                                <td class="border px-4 py-2">-</td>
                                <td class="border px-4 py-2">
                                    {{ $cpu?->brand }} {{ $cpu?->model }}
                                    @if ($cpu?->base_clock)
                                        {{ $cpu->base_clock }}GHz
                                        @if ($cpu?->boost_clock)
                                            (Boost {{ $cpu->boost_clock }}GHz)
                                        @endif
                                    @endif
                                </td>

                                <td class="border px-4 py-2">{{ $mboard?->brand }} {{ $mboard?->model }}</td>
                                <td class="border px-4 py-2">{{ $ram?->type }}
                                    {{ $ram?->capacity ? $ram->capacity . 'GB' : '' }}</td>
                                <td class="border px-4 py-2">{{ $drive?->type }}
                                    {{ $drive?->capacity ? $drive->capacity . 'GB' : '' }}</td>
                                <td class="border px-4 py-2">{{ $casing?->brand }} {{ $casing?->model }}</td>
                                <td class="border px-4 py-2">{{ $status }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button wire:click="closeModal"
                    class="mt-6 px-4 py-2 bg-gray-300 dark:bg-zinc-700 rounded hover:bg-gray-400 dark:hover:bg-zinc-600">
                    Close
                </button>
            </div>
        </div>
    @endif

</div>
