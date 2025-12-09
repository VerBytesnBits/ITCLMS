<div class="space-y-6">
    <!-- Header -->
    <livewire:dashboard-heading title="Components" subtitle="Track and manage all component parts inventory"
        icon="cpu-chip" gradient-from-color="#10b981" gradient-to-color="#047857" icon-color="text-green-600" />

    <!-- Summary -->
    <div x-data="{
        open: JSON.parse(localStorage.getItem('componentSummaryOpen')) || false,
        toggle() {
            this.open = !this.open;
            localStorage.setItem('componentSummaryOpen', JSON.stringify(this.open));
            if (!this.open) {
                $wire.set('tab', null); // reset tab when collapsed
            }
        }
    }"
        class="rounded-2xl  bg-white dark:bg-zinc-800 mt-4 mb-4 shadow-lg relative overflow-hidden outline-2 outline-offset-2 outline-blue-500/50 ">

        {{-- <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div> --}}
        <div class="flex items-center justify-between p-4 border-b">
            {{-- <h2 class="text-lg font-semibold">Total Components</h2>  --}}

            <flux:heading size="lg" level="1" class="text-lg flex items-center gap-2  text-zinc-600 ">
                Total Components
                <flux:tooltip hoverable>
                    <flux:button icon="information-circle" size="sm" variant="subtle" />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2 ">
                        <p>Click (View Component Statistics) to expand detailed summary of component inventory.</p>
                    </flux:tooltip.content>
                </flux:tooltip>
                <flux:tooltip hoverable>
                    <flux:button icon="printer" size="sm" variant="primary"
                        :href="route('components-part.components-parts-report')" wire:navigate
                        class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 
           dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 
           dark:shadow-lg dark:shadow-gray-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                        Component Reports
                    </flux:button>
                    <flux:tooltip.content class="max-w-[20rem] space-y-2">
                        <p>Component Reports</p>
                    </flux:tooltip.content>
                </flux:tooltip>
                {{-- gerating component summary report
                <livewire:components-part.component-summary-report :room-id="$roomId" :age="$age" :tab="$tab"
                    :key="$roomId . '-' . $age . '-' . $tab" /> --}}
            </flux:heading>

            <span class="text-xl font-bold text-zinc-400">
                {{ collect($this->componentSummary)->flatten(1)->sum('total') }} </span>
        </div>
        <button @click="toggle()"
            class="w-full flex items-center justify-between px-4 py-2 font-medium
           text-zinc-500 dark:text-zinc-200 
           bg-zinc-50 dark:bg-zinc-800 
           hover:bg-zinc-100 dark:hover:bg-zinc-700 
            transition">
            <flux:text size="sm"><span
                    x-text="open ? 'Hide Component Statistics' : 'View Component Statistics'"></span></flux:text>


            <!-- Chevron -->
            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-zinc-500 transition-transform duration-300"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-transition class="overflow-x-auto p-4 border-t shadow-inner space-y-6"
            x-data="stockTooltip()" x-init="init()">
            <!-- Tabs -->
            <div class="mb-4 border-b border-gray-200 dark:border-zinc-700">
                <nav class="-mb-px flex flex-wrap gap-2">
                    @php
                        $parts = ['CPU', 'Motherboard', 'RAM', 'Storage', 'GPU', 'PSU', 'Casing'];
                    @endphp

                    @foreach ($parts as $part)
                        <button wire:click="$set('tab', '{{ $part }}')"
                            class="px-4 py-2 text-sm font-medium border-b-2 uppercase transition-all
                        {{ $tab === $part
                            ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200' }}">
                            {{ $part }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Tables -->
            @if ($tab === 'All' || $tab === null)
                @foreach ($this->componentSummary as $part => $items)
                    <div class="space-y-2">
                        <h3
                            class="font-semibold text-lg text-gray-900 dark:text-gray-100 border-l-4 border-blue-500 pl-3">
                            {{ $part }}
                        </h3>

                        <div class="overflow-hidden border border-gray-200 dark:border-zinc-700 rounded-lg">
                            <table class="w-full text-sm text-gray-700 dark:text-gray-200">
                                <thead class="bg-blue-500 text-zinc-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left w-[40%]">Description</th>
                                        <th class="px-4 py-3 text-center w-[15%] cursor-pointer"
                                            wire:click="sortBy('total')">
                                            Quantity @if ($sortColumn === 'total')
                                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                            @endif
                                        </th>
                                        <th class="px-4 py-3 text-center w-[15%] cursor-pointer"
                                            wire:click="sortBy('available')">
                                            Available @if ($sortColumn === 'available')
                                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                            @endif
                                        </th>
                                        <th class="px-4 py-3 text-center w-[15%] cursor-pointer"
                                            wire:click="sortBy('in_use')">
                                            In Use @if ($sortColumn === 'in_use')
                                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                            @endif
                                        </th>
                                        <th class="px-4 py-3 text-center w-[15%] cursor-pointer"
                                            wire:click="sortBy('defective')">
                                            Defective @if ($sortColumn === 'defective')
                                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr
                                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-zinc-800 dark:even:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 transition">
                                            <!-- Description -->
                                            <td class="px-6 py-3 align-middle">
                                                <div class="flex items-center gap-2">
                                                    <span>{{ $item['description'] ?? '—' }}</span>

                                                    @if ($item['available'] == 0)
                                                        <span
                                                            class="px-2 py-0.5 text-xs font-semibold text-white bg-red-600 rounded-full">
                                                            Out of stock
                                                        </span>
                                                    @elseif ($item['available'] < $lowStockThreshold)
                                                        <span
                                                            class="px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                                            Low stock
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                            In stock
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Numeric Columns -->
                                            <td class="px-4 py-3 text-center font-medium">{{ $item['total'] }}</td>

                                            <td class="px-4 py-3 text-center font-medium stock-cell cursor-default"
                                                data-available="{{ $item['available'] }}"
                                                data-description="{{ $item['description'] }}"
                                                @mouseenter="show($event)" @mouseleave="hide()">
                                                {{ $item['available'] }}
                                            </td>

                                            <td class="px-4 py-3 text-center font-medium">{{ $item['in_use'] }}</td>
                                            <td class="px-4 py-3 text-center font-medium">{{ $item['defective'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @elseif (isset($this->componentSummary[$tab]) && count($this->componentSummary[$tab]) > 0)
                <!-- Single Tab Table -->
                <div class="overflow-hidden border border-gray-200 dark:border-zinc-700 rounded-lg">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-200">
                        <thead class="bg-blue-500 text-zinc-100">
                            <tr>
                                <th class="px-6 py-3 text-left w-[40%]">Description</th>
                                <th class="px-4 py-3 text-center w-[15%]">Quantity</th>
                                <th class="px-4 py-3 text-center w-[15%]">Available</th>
                                <th class="px-4 py-3 text-center w-[15%]">In Use</th>
                                <th class="px-4 py-3 text-center w-[15%]">Defective</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->componentSummary[$tab] as $item)
                                <tr
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-zinc-800 dark:even:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 transition">
                                    <td class="px-6 py-3 align-middle">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $item['description'] }}</span>
                                            @if ($item['available'] == 0)
                                                <span
                                                    class="px-2 py-0.5 text-xs font-semibold text-white bg-red-600 rounded-full">Out
                                                    of stock</span>
                                            @elseif ($item['available'] < $lowStockThreshold)
                                                <span
                                                    class="px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Low
                                                    stock</span>
                                            @else
                                                <span
                                                    class="px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">In
                                                    stock</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-medium">{{ $item['total'] }}</td>
                                    <td class="px-4 py-3 text-center font-medium">{{ $item['available'] }}</td>
                                    <td class="px-4 py-3 text-center font-medium">{{ $item['in_use'] }}</td>
                                    <td class="px-4 py-3 text-center font-medium">{{ $item['defective'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-gray-500 text-sm text-center">No records found for {{ $tab }}.</div>
            @endif
        </div>
    </div>


    <div
        class="relative bg-white dark:bg-zinc-800 rounded-2xl shadow-md border border-zinc-200 dark:border-zinc-700 overflow-hidden outline-2 outline-offset-2 outline-blue-500/50">
        <!-- Card Header -->
        {{-- <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div> --}}
        <div class="px-4 py-4 border-b border-gray-200 dark:border-zinc-700">

            {{-- <h2 class="text-lg font-semibold text-zinc-600 dark:text-zinc-100">Controls</h2> --}}
            <flux:heading size="lg" level="1" class="text-lg flex items-center gap-2  text-zinc-600 ">
                Controls
            </flux:heading>
            <flux:text class="text-xs">Search, filter, and add components</flux:text>
            {{-- <p class="text-sm text-gray-500 dark:text-gray-400">Search, filter, and add components</p> --}}
        </div>


        <!-- Card Body -->
        <div class="p-4 space-y-6">
            <!-- Filters Row -->
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-3 flex-wrap">

                <!-- Barcode Input -->
                <div class="flex flex-col w-full sm:w-auto flex-[2]">
                    <label for="barcodeInput"
                        class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 flex items-center gap-2">

                        Barcode
                    </label>
                    <div x-data x-init="$nextTick(() => $refs.barcodeInput.focus())" x-on:scan-complete.window="$refs.barcodeInput.focus()">
                        <flux:input id="barcodeInput" x-ref="barcodeInput" wire:model.lazy="scannedCode"
                            wire:keydown.enter="findComponentByBarcode" placeholder="Scan or enter barcode here..."
                            icon="barcode" autofocus />
                    </div>

                </div>
                {{-- <flux:input wire:model.live="search" placeholder="Search peripherals..." icon="magnifying-glass"
                    class="flex-[3] w-full min-w-[200px]" />  --}}
                <!-- Room Filter -->
                <div class="flex flex-col w-full sm:w-auto flex-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Room</label>
                    <flux:select wire:model.live="roomId" class="w-full">
                        <option value="">All Rooms</option>
                        @foreach ($labs as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Age Filter -->
                <div class="flex flex-col w-full sm:w-auto flex-1">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Date Range</label>
                    <flux:select wire:model.live="age" class="w-full">
                        <option value="">Select range</option>
                        <option value="new">New (within 1 year or under warranty)</option>
                        <option value="older_1month">Older than 1 month</option>
                        <option value="older_6months">Older than 6 months</option>
                        <option value="older_1year">Older than 1 year</option>
                        <option value="older_2years">Older than 2 years</option>
                        <option value="older_5years">Older than 5 years</option>
                    </flux:select>
                </div>

                <!-- Add Component Button -->
                <div class="flex items-end w-full sm:w-auto">
                    <flux:button icon="circle-plus" variant="primary" color="green" wire:click="openCreateModal"
                        class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
                        Add Component
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    @if (count($selectedComponents) > 0)
        <div class="mb-3 flex items-center gap-2">
            <button wire:click="$dispatch('open-delete-modal', { id: 'bulk', model: 'ComponentParts' })"
                class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
                :disabled="$wire.selectedComponents.length === 0">
                Delete Selected
            </button>
        </div>
    @endif

    <!-- Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg">

        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-blue-500 text-xs uppercase text-zinc-100">
                <tr>
                    <!-- Select All Checkbox -->
                    <th class="px-4 py-3">
                        <flux:checkbox wire:model.live="selectAll" />

                        {{-- <input type="checkbox" wire:model.live="selectAll"
                            class="rounded text-blue-600 focus:ring-blue-500"> --}}
                    </th>
                    <th class="px-4 py-3">Serial Number</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">PC :: Room</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-zinc-200">
                @forelse($components as $comp)
                    <tr
                        class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 odd:bg-white even:bg-zinc-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">

                        <!-- Row Checkbox -->
                        <td class="px-4 py-3">
                            <x-checkbox :value="$comp->id" wire:model.live="selectedComponents" />


                        </td>

                        <td class="px-4 py-3">{{ $comp->serial_number }}</td>
                        <td class="px-4 py-3">{{ $comp->part }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-base font-semibold rounded-full {{ $statusColors[$comp->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $comp->status }}
                            </span>

                        </td>
                        <td class="px-4 py-3">
                            <span class="text-base">{{ optional($comp->systemUnit)->name ?? '—' }}</span>::
                            <span class="text-base">{{ optional($comp->room)->name ?? '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">

                            <div x-data="{ open: false }" class="relative inline-flex">

                                <!-- Desktop Buttons -->
                                <div class="hidden sm:flex gap-2">
                                    <flux:tooltip hoverable>
                                        <!-- View -->
                                        <flux:button wire:click="openViewModal({{ $comp->id }})"
                                            variant="primary" icon="eye"
                                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                       hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 
                       dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                       dark:shadow-lg dark:shadow-blue-800/80 
                       font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                        </flux:button>
                                        <flux:tooltip.content class="max-w-[20rem] ">
                                            <p>View</p>
                                        </flux:tooltip.content>
                                    </flux:tooltip>

                                    <flux:tooltip hoverable>
                                        <!-- Edit -->
                                        <flux:button wire:click="openEditModal({{ $comp->id }})"
                                            variant="primary" icon="pencil"
                                            class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
                       hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 
                       dark:focus:ring-green-800 shadow-lg shadow-green-500/50 
                       dark:shadow-lg dark:shadow-green-800/80 
                       font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                        </flux:button>
                                        <flux:tooltip.content class="max-w-[20rem] ">
                                            <p>Modify</p>
                                        </flux:tooltip.content>
                                    </flux:tooltip>


                                    <!-- Delete -->
                                    <flux:button
                                        wire:click="$dispatch('open-delete-modal', [{{ $comp->id }}, 'ComponentParts'])"
                                        variant="primary" icon="trash"
                                        class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 
                       hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
                       dark:focus:ring-red-800 shadow-lg shadow-red-500/50 
                       dark:shadow-lg dark:shadow-red-800/80 
                       font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                    </flux:button>
                                </div>

                                <!-- Mobile Dropdown Button -->
                                <button @click="open = !open"
                                    class="sm:hidden inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-300 
                   dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v0m0 6v0m0 6v0" />
                                    </svg>
                                </button>

                                <!-- Mobile Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute right-0 mt-2 w-36 bg-white dark:bg-zinc-800 shadow-lg rounded-md z-50 sm:hidden">

                                    <button wire:click="openViewModal({{ $comp->id }})" @click="open = false"
                                        icon="eye"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700">
                                        View
                                    </button>

                                    <button wire:click="openEditModal({{ $comp->id }})" @click="open = false"
                                        icon="pencil"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700">
                                        Edit
                                    </button>

                                    <button
                                        wire:click="$dispatch('open-delete-modal', [{{ $comp->id }}, 'ComponentParts'])"
                                        icon="trash" @click="open = false"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-700/30">
                                        Delete
                                    </button>

                                </div>

                            </div>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-500">No components found.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>

    </div>

    <div class="mt-4">
        {{ $components->links() }}
    </div>
    <livewire:components.delete-modal />
    @if ($modal)
        @switch($modal)
            @case('create')
            @case('edit')
                <livewire:components-part.form :id="$id" :mode="$modal" wire:key="form-{{ $id ?? 'new' }}" />
            @break

            @case('view')
                <livewire:components-part.view :id="$id" wire:key="view-{{ $id }}" />
            @break
        @endswitch
    @endif


</div>
