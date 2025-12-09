<div class="space-y-6">

    <!-- Title -->
    <h2 class="text-center text-2xl font-semibold text-gray-900 dark:text-white">
        Assign Parts to {{ $unit->name ?? 'Unit' }}
    </h2>

    <!-- Legend -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
            Status Guide
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-gray-600 dark:text-gray-400">Assigned</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                <span class="text-gray-600 dark:text-gray-400">Selected Category</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-gray-300"></span>
                <span class="text-gray-600 dark:text-gray-400">None Assigned</span>
            </div>
        </div>
    </div>
    <div class="mt-4 space-y-2">
        @foreach ($tempComponents as $index => $component)
            <div class="flex justify-between items-center bg-green-100 px-3 py-1 rounded">
                <span class="text-sm font-medium">
                    {{ $component['part'] }} — {{ $component['brand'] }} {{ $component['model'] }}
                </span>

                <button wire:click="removeTempComponent({{ $index }})" class="text-red-600 text-sm">
                    ✕
                </button>
            </div>  
        @endforeach
    </div>

    <div class="mt-2 space-y-2">
        @foreach ($tempPeripherals as $index => $peripheral)
            <div class="flex justify-between items-center bg-blue-100 px-3 py-1 rounded">
                <span class="text-sm font-medium">
                    {{ $peripheral['type'] }} — {{ $peripheral['brand'] }} {{ $peripheral['model'] }}
                </span>

                <button wire:click="removeTempPeripheral({{ $index }})" class="text-red-600 text-sm">
                    ✕
                </button>
            </div>
        @endforeach
    </div>

    <!-- Tabs -->
    <div class="flex justify-center gap-6 border-b border-gray-200 dark:border-zinc-700 pb-2">
        <button wire:click="$set('tab','peripherals')"
            class="px-4 py-1 text-sm font-medium 
            {{ $tab === 'peripherals' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
            Peripherals
        </button>

        <button wire:click="$set('tab','components')"
            class="px-4 py-1 text-sm font-medium
            {{ $tab === 'components' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
            Components
        </button>
    </div>

    <!-- ============================ -->
    <!-- PERIPHERALS TAB -->
    <!-- ============================ -->
    @if ($tab === 'peripherals')
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- LEFT: CATEGORY LIST -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-3 shadow-sm">
                @foreach ($availablePeripherals as $type => $list)
                    @php
                        $isAssigned = !empty($selectedPeripherals[$type]);
                        $isSelected = $selectedType === $type;

                        $classes =
                            'flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer transition text-sm mb-2 ';

                        if ($isSelected) {
                            $classes .= 'bg-blue-100 dark:bg-blue-900 text-blue-700 font-semibold';
                        } elseif ($isAssigned) {
                            $classes .= 'bg-green-100 text-green-800';
                        } else {
                            $classes .= 'hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-700 dark:text-gray-300';
                        }
                    @endphp

                    <button wire:click="$set('selectedType','{{ $type }}')" class="{{ $classes }}">
                        <div class="flex items-center gap-3">

                            {{-- Green Assigned Indicator at the very start --}}
                            @if ($isAssigned)
                                <span class="text-green-600 text-sm">●</span>
                            @else
                                {{-- Keep spacing consistent even when not assigned --}}
                                <span class="w-3"></span>
                            @endif

                            {{-- Icon --}}
                            <img src="{{ asset($partIcons[$type] ?? 'images/icons/default.png') }}"
                                class="w-7 h-7 opacity-70">

                            {{-- Label --}}
                            <span class="capitalize truncate">{{ $type }}</span>
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- RIGHT: DEVICE LIST -->
            <div
                class="md:col-span-3 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-4 shadow-sm">

                @if ($selectedType)

                    @php
                        $available = $availablePeripherals[$selectedType] ?? null;
                        $assignedId = $selectedPeripherals[$selectedType] ?? null;
                        $assigned = $assignedId ? \App\Models\Peripheral::find($assignedId) : null;
                    @endphp

                    <!-- ASSIGNED CARD -->
                    @if ($assigned)
                        <div
                            class="bg-green-50 dark:bg-green-900/30 border border-green-300 rounded-lg p-3 mb-4 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700 dark:text-gray-200">
                                    {{ $assigned->brand }} {{ $assigned->model }}
                                    ({{ $assigned->serial_number }})
                                </span>
                                <button wire:click="unassign('{{ $selectedType }}')"
                                    class="px-3 py-1 rounded-md text-xs text-white bg-red-500">Unassign</button>
                            </div>
                        </div>
                    @endif

                    <!-- TITLE -->
                    <div class="flex flex-row gap-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-3">
                            Available {{ ucfirst($selectedType) }}
                        </h3>
                        <flux:button icon="circle-plus" wire:click="addInlineForm('peripheral', '{{ $selectedType }}')">
                            @if ($this->isTempAdded('peripheral', $type))
                                <span
                                    class="absolute -top-2 -right-2 bg-green-600 text-white text-[10px] px-2 py-[2px] rounded-full">
                                    Added
                                </span>
                            @endif
                        </flux:button>
                    </div>

                    <!-- SEARCH -->
                   <flux:input wire:model.live.debounce.500ms="searchPeripherals"
                            placeholder="Search components..."
                            autofocus />

                    <div class="mt-3 space-y-2">
                        @if ($available && $available->count())
                            @foreach ($available as $peripheral)
                                <div
                                    class="flex justify-between items-center bg-gray-50 dark:bg-zinc-800 p-3 rounded-lg text-sm border">
                                    <span>
                                        {{ $peripheral->brand }} {{ $peripheral->model }}
                                        <span class="text-gray-500 dark:text-gray-400">(S/N:
                                            {{ $peripheral->serial_number }})</span>
                                    </span>
                                    <button wire:click="assignSelected('{{ $selectedType }}', {{ $peripheral->id }})"
                                        class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs">Assign</button>
                                </div>
                            @endforeach

                            <div class="mt-2">
                                {{ $available->links('components.pagination.simple') }}
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 mt-6 text-center">No available peripherals.</p>
                        @endif
                    </div>
                @else
                    <p class="text-center text-gray-500 py-10">Select a peripheral category.</p>
                @endif

            </div>
        </div>
    @endif

    <!-- ============================ -->
    <!-- COMPONENTS TAB -->
    <!-- ============================ -->
    @if ($tab === 'components')
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- LEFT: PART LIST -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-3 shadow-sm">

                @foreach ($availableComponents as $part => $list)
                    @php
                        $isAssigned = !empty($selectedComponents[$part]);
                        $isSelected = $selectedPart === $part;

                        $classes =
                            'flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer transition text-sm mb-2 ';

                        if ($isSelected) {
                            $classes .= 'bg-blue-100 dark:bg-blue-900 text-blue-700 font-semibold';
                        } elseif ($isAssigned) {
                            $classes .= 'bg-green-100 text-green-700';
                        } else {
                            $classes .= 'hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-700 dark:text-gray-300';
                        }
                    @endphp

                    <button wire:click="$set('selectedPart','{{ $part }}')" class="{{ $classes }}">
                        <div class="flex items-center gap-3">

                            {{-- Green Assigned Indicator at the very start --}}
                            @if ($isAssigned)
                                <span class="text-green-600 text-sm">●</span>
                            @else
                                {{-- Keep spacing consistent even when not assigned --}}
                                <span class="w-3"></span>
                            @endif

                            {{-- Icon --}}
                            <img src="{{ asset($partIcons[$part] ?? 'images/icons/default.png') }}"
                                class="w-7 h-7 opacity-70">

                            {{-- Label --}}
                            <span class="capitalize truncate">{{ $part }}</span>
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- RIGHT: AVAILABLE COMPONENT LIST -->
            <div
                class="md:col-span-3 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-4 shadow-sm">

                @if ($selectedPart)

                    @php
                        $available = $availableComponents[$selectedPart] ?? null;
                        $assignedId = $selectedComponents[$selectedPart] ?? null;
                        $assigned = $assignedId ? \App\Models\ComponentParts::find($assignedId) : null;
                    @endphp

                    <!-- ASSIGNED CARD -->
                    @if ($assigned)
                        <div
                            class="bg-green-50 dark:bg-green-900/30 border border-green-300 rounded-lg p-3 mb-4 text-sm">
                            <div class="flex justify-between items-center">
                                <div>
                                    {{ $assigned->brand }} {{ $assigned->model }}
                                    <span class="text-gray-500 dark:text-gray-400">
                                        @if ($assigned->capacity)
                                            ({{ $assigned->capacity }} {{ $assigned->type }})
                                        @endif
                                        (SN: {{ $assigned->serial_number }})
                                    </span>
                                </div>
                                <button wire:click="unassignComponent('{{ $selectedPart }}')"
                                    class="px-3 py-1 rounded-md text-xs text-white bg-red-500">Unassign</button>
                            </div>
                        </div>
                    @endif

                    <!-- TITLE -->
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-3">
                        Available {{ ucfirst($selectedPart) }}
                    </h3>
                    <flux:button icon="circle-plus" wire:click="addInlineForm('component', '{{ $selectedPart }}')">
                        @if ($this->isTempAdded('component', $part))
                            <span
                                class="absolute -top-2 -right-2 bg-green-600 text-white text-[10px] px-2 py-[2px] rounded-full">
                                Added
                            </span>
                        @endif
                    </flux:button>


                  
                    <flux:input wire:model.live.debounce.500ms="searchComponents"
                            placeholder="Search components..."
                            autofocus />
                    <!-- LIST -->
                    <div class="mt-3 space-y-2">
                        @if ($available && $available->count())
                            @foreach ($available as $component)
                                <div
                                    class="flex justify-between items-center bg-gray-50 dark:bg-zinc-800 p-3 rounded-lg text-sm border">
                                    <span>
                                        {{ $component->brand }} {{ $component->model }}
                                        @if ($component->capacity)
                                            ({{ $component->capacity }} {{ $component->type }})
                                        @endif
                                        <span class="text-gray-500 dark:text-gray-400">
                                            (S/N: {{ $component->serial_number }})
                                        </span>
                                    </span>
                                    <button wire:click="assignComponent('{{ $selectedPart }}', {{ $component->id }})"
                                        class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs">Assign</button>
                                </div>
                            @endforeach

                            <div class="mt-3">
                                {{ $available->links('components.pagination.simple') }}
                            </div>
                        @else
                            <p class="text-center text-gray-500 mt-4">No components found.</p>
                        @endif
                    </div>
                @else
                    <p class="text-center text-gray-500 py-10">Select a component category.</p>
                @endif

            </div>
        </div>
    @endif

    <!-- OFF CANVAS ADD FORM -->
    <div x-data="{ open: @entangle('showInlineForm') }" x-show="open" x-transition class="fixed inset-0 z-50 flex justify-end"
        style="display: none;">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

        <!-- Panel -->
        <div class="relative w-full max-w-md h-full bg-white dark:bg-zinc-900 shadow-xl p-5 overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                Add New {{ ucfirst($inlineSelectedPart) }} ({{ ucfirst($inlineModelType) }})
            </h3>

            <!-- FORM -->
            <div class="space-y-3">

                <!-- COMMON FIELDS -->
                <input type="text" wire:model.defer="inline_brand" placeholder="Brand"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-zinc-800">

                <input type="text" wire:model.defer="inline_model" placeholder="Model"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-zinc-800">

                <input type="text" wire:model.defer="inline_serial_number" placeholder="Serial Number"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-zinc-800">



                <!-- COMPONENT FIELDS -->
                @if ($inlineModelType === 'component')
                    @if (in_array($inlineSelectedPart, ['RAM', 'Storage']))
                        <input type="text" wire:model.defer="inline_capacity" placeholder="Capacity (GB)"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-zinc-800">
                    @endif

                    @if ($inlineSelectedPart === 'CPU')
                        {{-- <input type="text" wire:model.defer="inline_clock_speed" placeholder="Clock Speed (GHz)"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-zinc-800"> --}}
                        <flux:select label="Speed" wire:model.defer="inline_clock_speed">
                            <option value="">Select Speed</option>
                            <option value="2.5GHz">2.5GHz</option>
                            <option value="3.2GHz">3.2GHz</option>
                            <option value="3.6GHz">3.6GHz</option>
                            <option value="3.9GHz">3.9GHz</option>
                        </flux:select>
                    @endif
                @endif

                <!-- PERIPHERAL FIELDS -->
                @if ($inlineModelType === 'peripheral')
                    <input type="text" wire:model.defer="inline_size" placeholder="Size (optional)"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-zinc-800">

                    <input type="text" wire:model.defer="inline_connection_type" placeholder="Connection Type"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-zinc-800">
                @endif
            </div>

            <!-- ACTIONS -->
            <div class="mt-6 flex gap-3">
                <button wire:click="saveInlineItem"
                    class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">
                    Save
                </button>

                <button @click="open = false"
                    class="flex-1 px-4 py-2 bg-gray-300 dark:bg-zinc-700 text-gray-800 dark:text-white rounded-md text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>


</div>
