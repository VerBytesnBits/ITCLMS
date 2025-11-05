<div class="space-y-6">
    <!-- Header -->
    <livewire:dashboard-heading title="Peripherals" subtitle="Track and manage all peripheral inventory" icon="cube"
        gradient-from-color="#3b82f7" gradient-to-color="#1e40af" icon-color="text-blue-600" />

    @if ($scannedPeripheral)
        <div class="bg-white border rounded-xl p-4 mt-3 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800">Peripheral Details</h2>
            <div class="mt-2 space-y-1 text-sm">
                <p><strong>Type:</strong> {{ $scannedPeripheral->type }}</p>
                <p><strong>Brand:</strong> {{ $scannedPeripheral->brand }}</p>
                <p><strong>Model:</strong> {{ $scannedPeripheral->model }}</p>
                <p><strong>Serial Number:</strong> {{ $scannedPeripheral->serial_number }}</p>
                <p><strong>Status:</strong> {{ $scannedPeripheral->status }}</p>
                @if ($scannedPeripheral->barcode_path)
                    <img src="{{ asset($scannedPeripheral->barcode_path) }}" alt="Barcode" class="h-20 mt-2">
                @endif
            </div>
        </div>
    @endif

    <!-- Peripheral Summary -->
    <div x-data="{
        open: JSON.parse(localStorage.getItem('peripheralSummaryOpen')) || false,
        toggle() {
            this.open = !this.open;
            localStorage.setItem('peripheralSummaryOpen', JSON.stringify(this.open));
            if (!this.open) {
                $wire.set('tab', null); // reset tab when collapsed
            }
        }
    }"
        class="rounded-2xl bg-white dark:bg-zinc-800 mt-4 mb-4 shadow-lg relative overflow-hidden outline-2 outline-offset-2 outline-blue-500/50">

        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <flux:heading size="lg" level="1"
                class="text-lg flex items-center gap-2 text-zinc-600 dark:text-zinc-50">
                Total Peripherals

                <flux:tooltip hoverable>
                    <flux:button icon="information-circle" size="sm" variant="subtle" />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2">
                        <p>Click (View Peripheral Statistics) to expand detailed summary of peripheral inventory.</p>
                    </flux:tooltip.content>
                </flux:tooltip>

                <flux:tooltip hoverable>
                    <flux:button icon="printer" size="sm" variant="subtle" />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2">
                        <p>Print Peripheral Reports</p>
                    </flux:tooltip.content>
                </flux:tooltip>
            </flux:heading>

            <span class="text-xl font-bold text-zinc-700">
                {{ collect($this->peripheralSummary)->flatten(1)->sum('total') }}
            </span>
        </div>

        <!-- Toggle -->
        <button @click="toggle()"
            class="w-full flex items-center justify-between px-4 py-2 font-medium
        text-zinc-500 dark:text-zinc-200 
        bg-zinc-50 dark:bg-zinc-800 
        hover:bg-zinc-100 dark:hover:bg-zinc-700 
        rounded-lg transition">
            <flux:text size="sm">
                <span x-text="open ? 'Hide Peripheral Statistics' : 'View Peripheral Statistics'"></span>
            </flux:text>

            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-zinc-500 transition-transform duration-300"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Summary Table Section -->
        <div x-show="open" x-transition class="overflow-x-auto p-4 border-t shadow-inner space-y-6"
            x-data="stockTooltip()" x-init="init()">

            <!-- Tabs -->
            <div class="mb-4 border-b border-gray-200 dark:border-zinc-700">
                <nav class="-mb-px flex flex-wrap gap-2">
                    @php
                        $parts = [
                            'Monitor',
                            'Keyboard',
                            'Mouse',
                            'Printer',
                            'Speaker',
                            'Projector',
                            'AVR',
                            'UPS',
                            'Webcam',
                        ];
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
                @foreach ($this->peripheralSummary as $part => $items)
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
                                            <td class="px-6 py-3">
                                                <div class="flex items-center gap-2">
                                                    <span>{{ $item['description'] }}</span>
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
            @elseif (isset($this->peripheralSummary[$tab]) && count($this->peripheralSummary[$tab]) > 0)
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
                            @foreach ($this->peripheralSummary[$tab] as $item)
                                <tr
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-zinc-800 dark:even:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 transition">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $item['description'] }}</span>
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
        {{-- 
        <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div> --}}
        <div class="px-4 py-4 border-b border-gray-200 dark:border-zinc-700">

            {{-- <h2 class="text-lg font-semibold text-zinc-600 dark:text-zinc-100">Controls</h2> --}}
            <flux:heading size="lg" level="1" class="text-lg flex items-center gap-2  text-zinc-600 ">
                Controls
            </flux:heading>
            <flux:text class="text-xs">Search, filter, and add peripheral</flux:text>

        </div>

        <!-- Card Body -->
        <div class="p-4 space-y-6">
            <!-- Filters Row -->
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-3 flex-wrap">

                <!-- Barcode Input -->
                <div class="flex flex-col w-full sm:w-auto flex-[2]">
                    <label for="barcodeInput"
                        class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 flex items-center gap-2">

                        Scan Barcode
                    </label>
                    <div x-data x-init="$nextTick(() => $refs.barcodeInput.focus())" x-on:scan-complete.window="$refs.barcodeInput.focus()">
                        <flux:input id="barcodeInput" x-ref="barcodeInput" wire:model.lazy="scannedCode"
                            wire:keydown.enter="findPeripheralByBarcode" placeholder="Scan or enter barcode here..."
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

                <!-- Add Peripheral Button -->
                <div class="flex items-end w-full sm:w-auto">
                    <flux:button icon="plus" variant="primary" color="green" wire:click="openCreateModal"
                        class="w-full sm:w-auto rounded-xl shadow-md hover:shadow-lg transition">
                        Add Peripheral
                    </flux:button>
                </div>
            </div>
        </div>

    </div>
    @if (count($selectedPeripherals) > 0)
        <div class="mb-3 flex items-center gap-2">
            <button wire:click="$dispatch('open-delete-modal', { id: 'bulk', model: 'Peripherals' })"
                class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
                :disabled="$wire.selectedPeripherals.length === 0">
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
                    <th class="px-4 py-3">
                        <flux:checkbox wire:model.live="selectAll" />


                    </th>
                    <th class="px-4 py-3">Serial Number</th>
                    <th class="px-4 py-3">Category</th>
                    {{-- <th class="px-4 py-3">Condition</th> --}}
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">


                @forelse($peripherals as $peripheral)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50 odd:bg-white even:bg-zinc-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
                        <td class="px-4 py-3">
                            <x-checkbox :value="$peripheral->id" wire:model.live="selectedPeripherals" />
                        </td>
                        <td class="px-4 py-3">{{ $peripheral->serial_number }}</td>
                        <td class="px-4 py-3">{{ $peripheral->type }}</td>
                        {{-- <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $conditionColors[$peripheral->condition] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $peripheral->condition }}
                            </span>
                        </td> --}}


                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$peripheral->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $peripheral->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <!-- Actions -->
                            <div x-data="{ open: false }" class="relative inline-flex w-full sm:w-auto">
                                <!-- View -->
                                <button wire:click="openViewModal({{ $peripheral->id }})"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs md:text-sm font-medium border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-l-md flex-1 sm:flex-none">
                                    <flux:icon.eye />
                                </button>
                                <!-- Dropdown -->
                                <button @click="open = !open" x-ref="toggleBtn" type="button"
                                    class="inline-flex items-center justify-center px-2 py-2 border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-r-md border-l-0 flex-1 sm:flex-none">
                                    <svg class="h-4 w-4 md:h-5 md:w-5" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                            <button wire:click="openEditModal({{ $peripheral->id }})"
                                                @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.pencil class="h-4 w-4" />
                                                <span>Edit</span>
                                            </button>
                                            {{-- <button wire:click="deletePeripheral({{ $peripheral->id }})"
                                                @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-700">
                                                <flux:icon.trash class="h-4 w-4" />
                                                <span>Delete</span>
                                            </button> --}}
                                            <button
                                                wire:click="$dispatch('open-delete-modal', [{{ $peripheral->id }}, 'Peripheral'])"
                                                @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-700">
                                                <flux:icon.trash class="h-4 w-4" />
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">No peripherals found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $peripherals->links() }}
    </div>
    <!-- Include modal once -->
    <livewire:components.delete-modal />
    <!-- Modals -->
    @if ($modal)
        @switch($modal)
            @case('create')
            @case('edit')
                <livewire:peripherals.peripheral-form :id="$id" :mode="$modal"
                    wire:key="peripheral-form-{{ $id ?? 'new' }}" />
            @break

            @case('view')
                <livewire:peripherals.peripheral-view :id="$id" wire:key="peripheral-view-{{ $id }}" />
            @break
        @endswitch
    @endif

    {{-- <x-scroll-to-up /> --}}
</div>
