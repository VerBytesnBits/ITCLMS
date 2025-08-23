<?php
use App\Support\PartsConfig;
?>

<div class="p-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        {{-- Search Input --}}
        {{-- <div class="order-3 md:order-1 w-full md:w-auto max-w-xs">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search components/peripherals..."
                class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div> --}}
        <flux:input icon="magnifying-glass" wire:model.debounce.300ms="search" autocomplete="off"
            placeholder="Search unit/components/peripherals..." />

        {{-- Buttons Container --}}
        <div class="flex flex-col md:flex-row gap-4 order-1 md:order-2 w-full md:w-auto max-w-xs">


            <livewire:unit-export-pdf :rooms="$rooms" />
            {{-- <button wire:click="openCreateModal"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                Add Unit
            </button> --}}
            <flux:button variant="primary" color="green" wire:click="openCreateModal">Add unit</flux:button>


        </div>
    </div>

    {{-- Legends and Filters --}}
    <div class="flex flex-col md:flex-row justify-between gap-6 text-sm mb-6 items-center">
        <x-status-legend :counts="$this->counts" />



        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto items-center">
            <flux:subheading class="whitespace-nowrap">Filter by:</flux:subheading>
            <div class="flex justify-start items-center gap-2">
                {{-- <flux:select wire:model.live="filterStatus">
                    <flux:select.option value="">All Status</flux:select.option>
                    <flux:select.option value="Operational">Operational</flux:select.option>
                    <flux:select.option value="Non-Operational">Non-Operational</flux:select.option>
                    <flux:select.option value="Needs Repair">Needs Repair</flux:select.option>
                </flux:select> --}}


                <flux:dropdown>
                    <flux:button icon:trailing="plus">Status</flux:button>
                    <flux:menu>
                        <flux:menu.radio.group wire:model.live="filterStatus">
                            <flux:menu.radio checked value="">All Status</flux:menu.radio>
                            <flux:menu.radio value="Operational">Operational</flux:menu.radio>
                            <flux:menu.radio value="Non-Operational">Non-Operational</flux:menu.radio>
                            <flux:menu.radio value="Needs Repair">Needs Repair</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
                <flux:dropdown>
                    <flux:button icon:trailing="plus">Type</flux:button>
                    <flux:menu>
                        <flux:menu.radio.group wire:model.live="filterType">
                            <flux:menu.radio checked value="">All Types</flux:menu.radio>
                            <flux:menu.radio value="component">Component</flux:menu.radio>
                            <flux:menu.radio value="peripheral">Peripheral</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>

                <flux:dropdown>
                    <flux:button icon:trailing="plus">Room</flux:button>
                    <flux:menu>
                        <flux:menu.radio.group wire:model.live="filterRoomId">
                            <flux:menu.radio checked value="">All Rooms</flux:menu.radio>
                            @foreach ($rooms as $room)
                                <flux:menu.radio value="{{ $room->id }}">{{ $room->name }}</flux:menu.radio>
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
            </div>

        </div>
    </div>

    <livewire:system-units.unit-table :$units />

   




    {{-- Modals --}}

    @if ($modal === 'report')
        <livewire:unit-reports.report-form :unit-id="$selectedUnitId" :key="'report-' . $selectedUnitId" />
    @endif

    @if ($modal === 'create')
        <livewire:system-units.unit-form :key="'create'" />
    @endif

    @if ($modal === 'edit' && $id)
        <livewire:system-units.unit-form :unit-id="$id" :key="'edit-' . $id" />
    @endif

    @if ($modal === 'view' && $viewUnit)
        <div x-data @keydown.escape.window="$wire.closeModal()" {{-- closes on Esc key too --}}
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 overflow-auto">

            <div @click.outside="$wire.closeModal()" {{-- closes when clicking outside --}}
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl animate-[fade-in-scale_0.2s_ease-out] relative">



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

                    foreach ($componentTypes as $type) {
                        $items = $viewUnit->$type;
                        if ($items instanceof \Illuminate\Support\Collection) {
                            foreach ($items as $part) {
                                $groupedParts['Components'][] = [
                                    'label' => $labels[$type] ?? ucfirst($type),
                                    'part' => $part,
                                ];
                            }
                        } elseif ($items) {
                            $groupedParts['Components'][] = [
                                'label' => $labels[$type] ?? ucfirst($type),
                                'part' => $items,
                            ];
                        }
                    }

                    foreach ($peripheralTypes as $type) {
                        $items = $viewUnit->$type;
                        if ($items instanceof \Illuminate\Support\Collection) {
                            foreach ($items as $part) {
                                $groupedParts['Peripherals'][] = [
                                    'label' => $labels[$type] ?? ucfirst($type),
                                    'part' => $part,
                                ];
                            }
                        } elseif ($items) {
                            $groupedParts['Peripherals'][] = [
                                'label' => $labels[$type] ?? ucfirst($type),
                                'part' => $items,
                            ];
                        }
                    }
                @endphp


                @if (empty($groupedParts['Components']) && empty($groupedParts['Peripherals']))
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
