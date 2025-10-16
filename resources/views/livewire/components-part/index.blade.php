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
        class="border rounded-2xl  bg-white dark:bg-zinc-800 mt-4 mb-4 shadow-lg relative overflow-hidden ">

        <div class="absolute top-0 left-0 w-full h-2 bg-blue-500"></div>
        <div class="flex items-center justify-between p-4 border-b">
            {{-- <h2 class="text-lg font-semibold">Total Components</h2>  --}}

            <flux:heading size="lg" level="1"
                class="text-lg flex items-center gap-2  text-zinc-600 dark:text-zinc-50 ">
                Total Components
                <flux:tooltip hoverable>
                    <flux:button icon="information-circle" size="sm" variant="subtle" />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2 ">
                        <p>Click (View Component Statistics) to expand detailed summary of component inventory.</p>
                    </flux:tooltip.content>
                </flux:tooltip>
                {{-- gerating component summary report --}}
                <livewire:components-part.component-summary-report :room-id="$roomId" :age="$age" :tab="$tab"
                    :key="$roomId . '-' . $age . '-' . $tab" />
            </flux:heading>

            <span class="text-xl font-bold text-zinc-700">
                {{ collect($this->componentSummary)->flatten(1)->sum('total') }} </span>
        </div>
        <button @click="toggle()"
            class="w-full flex items-center justify-between px-4 py-2 font-medium
           text-zinc-500 dark:text-zinc-200 
           bg-zinc-50 dark:bg-zinc-800 
           hover:bg-zinc-100 dark:hover:bg-zinc-700 
           rounded-lg transition">
            <flux:text size="sm"><span
                    x-text="open ? 'Hide Component Statistics' : 'View Component Statistics'"></span></flux:text>


            <!-- Chevron -->
            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-zinc-500 transition-transform duration-300"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" x-transition class="overflow-x-auto p-4 border-t shadow-inner " x-data="stockTooltip()"
            x-init="init()">
            {{-- <h3 class="text-md font-semibold mb-2">Component Inventory</h3> --}}
            <!-- Tabs -->
            <div class="mb-4 border-b border-gray-200 dark:border-zinc-700">
                <nav class="-mb-px flex flex-wrap gap-2">
                    @php
                        //'All',
                        $parts = ['CPU', 'Motherboard', 'RAM', 'Storage', 'GPU', 'PSU', 'Casing'];
                    @endphp

                    @foreach ($parts as $part)
                        <button wire:click="$set('tab', '{{ $part }}')"
                            class="px-4 py-2 text-sm font-medium border-b-2 uppercase 
                        {{ $tab === $part
                            ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200' }}">
                            {{ $part }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Summary Table -->
            @if ($tab === 'All' || $tab === null)
                {{-- Show all components --}}
                @foreach ($this->componentSummary as $part => $items)
                    <h3 class="font-bold text-gray-900 dark:text-gray-200 mt-4">{{ $part }}</h3>
                    <table class="w-full border-collapse mb-4">
                        <thead>
                            <tr class="bg-blue-500  text-zinc-100">
                                <th class="px-4 py-2 text-left">Description</th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('total')">
                                    Quantity @if ($sortColumn === 'total')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('available')">
                                    Available @if ($sortColumn === 'available')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('in_use')">
                                    In Use @if ($sortColumn === 'in_use')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('defective')">
                                    Defective @if ($sortColumn === 'defective')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                {{-- <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('maintenance')">
                                    Maintenance @if ($sortColumn === 'maintenance')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th> --}}
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('Decommission')">
                                    Decommission @if ($sortColumn === 'decommission')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                {{-- <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('salvage')">
                                    Salvage @if ($sortColumn === 'salvage')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr class="odd:bg-white even:bg-zinc-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
                                    <td class="px-6 py-4">
                                        {{ $item['description'] }}
                                        @if ($item['available'] == 0)
                                            <span
                                                class="ml-2 px-2 py-0.5 text-xs font-semibold text-white bg-red-600 rounded-full">Out
                                                of stock</span>
                                        @elseif ($item['available'] < $lowStockThreshold)
                                            <span
                                                class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-500 bg-yellow-100 rounded-full">Low
                                                stock</span>
                                        @else
                                            <span
                                                class="ml-2 px-2 py-0.5 text-xs font-semibold text-green-500 bg-green-100 rounded-full">In
                                                stock</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $item['total'] }}</td>
                                    <td class="px-6 py-4 text-center stock-cell cursor-default"
                                        data-available="{{ $item['available'] }}"
                                        data-description="{{ $item['description'] }}" @mouseenter="show($event)"
                                        @mouseleave="hide()"> {{ $item['available'] }} </td>
                                    <td class="px-4 py-2 text-center">{{ $item['in_use'] }}</td>
                                    <td class="px-4 py-2 text-center">{{ $item['defective'] }}</td>
                                    {{-- <td class="px-4 py-2 text-center">{{ $item['maintenance'] }}</td> --}}
                                    <td class="px-4 py-2 text-center">{{ $item['junk'] }}</td>
                                    {{-- <td class="px-4 py-2 text-center">{{ $item['salvage'] }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @elseif (isset($this->componentSummary[$tab]) && count($this->componentSummary[$tab]) > 0)
                {{-- Show only selected tab --}}
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-500  text-zinc-100">
                            <th class="px-4 py-2 text-left">Description</th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('total')">
                                Quantity @if ($sortColumn === 'total')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('available')">
                                Available @if ($sortColumn === 'available')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('in_use')">
                                In Use @if ($sortColumn === 'in_use')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('defective')">
                                Defective @if ($sortColumn === 'defective')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                @endif
                            </th>
                            {{-- <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('maintenance')">
                                Maintenance @if ($sortColumn === 'maintenance')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                @endif
                            </th> --}}
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('Decommission')">
                                Decommission @if ($sortColumn === 'decommission')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                @endif
                            </th>
                            {{-- <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('salvage')">
                                Salvage @if ($sortColumn === 'salvage')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                @endif
                            </th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->componentSummary[$tab] as $item)
                            <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-zinc-800 dark:even:bg-zinc-700 ">
                                <td class="px-6 py-4">
                                    {{ $item['description'] }}
                                    @if ($item['available'] == 0)
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-semibold text-white bg-red-600 rounded-full">Out
                                            of stock</span>
                                    @elseif ($item['available'] < $lowStockThreshold)
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-500 bg-yellow-100 rounded-full">Low
                                            stock</span>
                                    @else
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-semibold text-green-500 bg-green-100 rounded-full">In
                                            stock</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">{{ $item['total'] }}</td>
                                <td class="px-6 py-4 text-center stock-cell cursor-default"
                                    data-available="{{ $item['available'] }}"
                                    data-description="{{ $item['description'] }}" @mouseenter="show($event)"
                                    @mouseleave="hide()"> {{ $item['available'] }} </td>
                                <td class="px-4 py-2 text-center">{{ $item['in_use'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $item['defective'] }}</td>
                                {{-- <td class="px-4 py-2 text-center">{{ $item['maintenance'] }}</td> --}}
                                <td class="px-4 py-2 text-center">{{ $item['junk'] }}</td>
                                {{-- <td class="px-4 py-2 text-center">{{ $item['salvage'] }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-gray-500 text-sm">No records found for {{ $tab }}.</div>
            @endif
            {{-- </div> --}}
        </div>
    </div>


    <div
        class="relative bg-white dark:bg-zinc-800 rounded-2xl shadow-md border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <!-- Card Header -->
        <div class="absolute top-0 left-0 w-full h-2 bg-blue-500"></div>
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
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Search -->
                <flux:input wire:model.live="search" placeholder="Search components..." icon="magnifying-glass"
                    class="flex-[3] w-full min-w-[200px]" />

                <!-- Room Filter -->
                <flux:select wire:model.live="roomId" class="flex-1 w-full min-w-[160px]">
                    <option value="">All Rooms</option>
                    @foreach ($labs as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                    @endforeach
                </flux:select>
                <!-- Age Filter -->
                <flux:select wire:model.live="age" class="flex-1 w-full min-w-[160px]">
                    <option value="">All</option>
                    <option value="new">New (within 1 year or under warranty)</option>
                    <option value="older_1month">Older than 1 month</option>
                    <option value="older_6months">Older than 6 months</option>
                    <option value="older_1year">Older than 1 year</option>
                    <option value="older_2years">Older than 2 years</option>
                    <option value="older_5years">Older than 5 years</option>
                </flux:select>
                <!-- Add Component -->
                <flux:button variant="primary" color="green" wire:click="openCreateModal"
                    class="w-full sm:w-auto rounded-xl shadow-md hover:shadow-lg transition">
                    + Add Component
                </flux:button>
            </div>
        </div>
    </div>


    <!-- Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg">

        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-blue-500 text-xs uppercase text-zinc-100">
                <tr>
                    {{-- <th class="px-4 py-3">#</th> --}}
                    {{-- <th class="px-4 py-3">Unit</th> --}}
                    <th class="px-4 py-3">Serial Number</th>
                    {{-- <th class="px-4 py-3">Brand</th>
                    <th class="px-4 py-3">Model</th> --}}
                    <th class="px-4 py-3">Category</th>
                    {{-- <th class="px-4 py-3">Condition</th> --}}
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse($components as $component)
                    <tr wire:key="component-row-{{ $component->id }}"
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 odd:bg-white even:bg-zinc-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">

                        {{-- <td class="px-4 py-3">{{ optional($component->systemUnit)->name ?? '—' }}</td> --}}
                        <td class="px-4 py-3">{{ $component->serial_number }}</td>
                        <td class="px-4 py-3">{{ $component->part }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$component->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $component->status }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center space-x-2">
                            <!-- Actions -->
                            <div x-data="{ open: false }" class="relative inline-flex w-full sm:w-auto">
                                <!-- Main Action -->
                                <button wire:click="openViewModal({{ $component->id }})"
                                    class="inline-flex items-center justify-center px-3 py-2 text-xs md:text-sm font-medium
                    border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800
                    text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-700
                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                    rounded-l-md flex-1 sm:flex-none">
                                    <flux:icon.eye />
                                </button>

                                <!-- Dropdown Toggle -->
                                <button @click="open = !open" x-ref="toggleBtn" type="button"
                                    class="inline-flex items-center justify-center px-2 py-2 border border-gray-300 dark:border-zinc-700
                        bg-white dark:bg-zinc-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-zinc-700
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                        rounded-r-md border-l-0 flex-1 sm:flex-none">
                                    <svg class="h-4 w-4 md:h-5 md:w-5" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu (Teleported) -->
                                <template x-teleport="body">
                                    <div x-show="open" x-transition @click.away="open = false" x-cloak x-data
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
                                            <button wire:click="openEditModal({{ $component->id }})"
                                                @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.pencil class="h-4 w-4" />
                                                <span>Edit</span>
                                            </button>

                                            <button wire:click="deleteComponent({{ $component->id }})"
                                                @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-700">
                                                <flux:icon.trash class="h-4 w-4" />
                                                <span>Delete</span>
                                            </button>

                                            <!-- Child Livewire component -->
                                            <livewire:manage-item :model-class="App\Models\ComponentParts::class" :item-id="$component->id"
                                                :key="'manage-item-' . $component->id" />
                                        </div>
                                    </div>
                                </template>
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

    {{-- <x-scroll-to-up /> --}}
</div>
