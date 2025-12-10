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
                            $classes .= 'hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-gray-700 bg-gray-100 dark:bg-gray-300';
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
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-3">
                            Available {{ ucfirst($selectedType) }}
                        </h3>
                        <flux:button wire:click="addInlineForm('peripheral', '{{ $selectedType }}')" variant="primary" color="green">
                            <flux:icon.circle-plus class="h-4 w-4" />
                            @if ($this->isTempAdded('peripheral', $type))
                                <span
                                    class="absolute -top-2 -right-2 bg-green-600 text-white text-[10px] px-2 py-[2px] rounded-full">
                                    Added
                                </span>
                            @endif
                        </flux:button>
                    </div>

                    <!-- SEARCH -->
                    <flux:input wire:model.live.debounce.500ms="searchPeripherals" placeholder="Search components..."
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
                                        @if ($mode === 'create') disabled @endif
                                        class="px-3 py-1 text-xs rounded-md
                                         {{ $mode === 'create' ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                                        Assign
                                    </button>

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
                            $classes .= 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 font-semibold';
                        } elseif ($isAssigned) {
                            $classes .= 'bg-green-100 text-green-700';
                        } else {
                            $classes .= 'hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-gray-700 bg-gray-100 dark:bg-gray-300';
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

                    <div class="flex items-center justify-between mb-3">
                        <!-- TITLE -->
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">
                            Available {{ ucfirst($selectedPart) }}
                        </h3>

                        <!-- BUTTON -->
                        <div class="relative">
                            <flux:button wire:click="addInlineForm('component', '{{ $selectedPart }}')" variant="primary" color="green">
                                <flux:icon.circle-plus class="h-4 w-4" />
                                @if ($this->isTempAdded('component', $selectedPart))
                                    <span
                                        class="absolute -top-2 -right-2 bg-green-600 text-white text-[10px] px-2 py-[2px] rounded-full">
                                        Added
                                    </span>
                                @endif
                            </flux:button>
                        </div>
                    </div>



                    <flux:input wire:model.live.debounce.500ms="searchComponents" placeholder="Search components..."
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
                                        @if ($mode === 'create') disabled @endif
                                        class="px-3 py-1 text-xs rounded-md
                                         {{ $mode === 'create' ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                                        Assign
                                    </button>


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
    <div x-data="{ open: @entangle('showInlineForm') }" x-show="open" x-transition:enter="transition duration-500 ease-out"
        class="fixed inset-0 z-50 flex justify-end" style="display: none;">



        <div x-transition:enter="transform transition duration-500 ease-out"
            x-transition:enter-start="translate-x-full scale-95" x-transition:enter-end="translate-x-0 scale-100"
            x-transition:leave="transform transition duration-300 ease-in"
            x-transition:leave-start="translate-x-0 scale-100" x-transition:leave-end="translate-x-full scale-95"
            class="relative w-full max-w-md h-full bg-white dark:bg-zinc-900 shadow-xl rounded-l-2xl p-6 overflow-y-auto flex flex-col">

            <div class="flex items-center justify-between mb-6 border-b border-gray-100 dark:border-zinc-800 pb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    Add New {{ ucfirst($inlineSelectedPart) }} ({{ ucfirst($inlineModelType) }})
                </h3>
                <button @click="open = false"
                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition duration-150 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4 flex-1 overflow-y-auto pr-2 -mr-2">

                {{-- Brand --}}
                <flux:input wire:model.defer="inline_brand" label="Brand" placeholder="Brand" />

                {{-- Model --}}
                <flux:input wire:model.defer="inline_model" label="Model" placeholder="Model" />

                {{-- Serial Number --}}
                <flux:input wire:model.defer="inline_serial_number" label="Serial Number"
                    placeholder="Serial Number" />

                {{-- COMPONENT FIELDS --}}
                @if ($inlineModelType === 'component')

                    {{-- Capacity (RAM / Storage) --}}
                    @if (in_array($inlineSelectedPart, ['RAM', 'Storage']))
                        <flux:input wire:model.defer="inline_capacity" label="Capacity (GB)"
                            placeholder="Capacity (GB)" type="number" />
                    @endif

                    {{-- CPU Clock Speed --}}
                    @if ($inlineSelectedPart === 'CPU')
                        <flux:select wire:model.defer="inline_clock_speed" label="Clock Speed"
                            placeholder="Select Speed">
                            <option value="2.5GHz">2.5GHz</option>
                            <option value="3.2GHz">3.2GHz</option>
                            <option value="3.6GHz">3.6GHz</option>
                            <option value="3.9GHz">3.9GHz</option>
                        </flux:select>
                    @endif

                @endif

                {{-- PERIPHERAL FIELDS --}}
                @if ($inlineModelType === 'peripheral')
                    {{-- Size --}}
                    <flux:input wire:model.defer="inline_size" label="Size" placeholder="Size (optional)" />

                    {{-- Connection Type --}}
                    <flux:input wire:model.defer="inline_connection_type" label="Connection Type"
                        placeholder="Connection Type" />
                @endif

            </div>


            <div class="mt-8 pt-4 border-t border-gray-100 dark:border-zinc-800 flex gap-3">
                <button wire:click="saveInlineItem"
                    class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-base shadow-md transition duration-200 transform hover:scale-[1.02]">
                    Save Item
                </button>

                <button @click="open = false"
                    class="flex-1 px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-700 dark:text-gray-200 font-medium rounded-lg text-base hover:bg-gray-300 dark:hover:bg-zinc-600 transition duration-200">
                    Cancel
                </button>
            </div>

        </div>
    </div>


</div>
