<div>
    <!-- Header -->
    <livewire:dashboard-heading title="Peripherals" subtitle="Track and manage all peripheral inventory"
        icon="cube" gradient-from-color="#3b82f6" gradient-to-color="#1e40af" icon-color="text-blue-600" />

    <!-- Summary -->
    <div x-data="{
        open: JSON.parse(localStorage.getItem('peripheralSummaryOpen')) || false,
        toggle() {
            this.open = !this.open;
            localStorage.setItem('peripheralSummaryOpen', JSON.stringify(this.open));
            if (!this.open) {
                $wire.set('tab', null); // reset tab when collapsed
            }
        }
    }" class="border rounded-lg shadow-sm bg-white dark:bg-zinc-800 mt-4 mb-4">
        <!-- Header row -->
        <div class="flex items-center justify-between p-4 border-b">
            <flux:heading class="flex items-center gap-2 !text-2xl">
                Total Peripherals
                <flux:tooltip hoverable>
                    <flux:button icon="information-circle" size="sm" variant="ghost" />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2">
                        <p>Click (View Statistics) to expand detailed summary of peripheral inventory.</p>
                    </flux:tooltip.content>
                </flux:tooltip>
                <flux:tooltip hoverable>
                    <flux:button icon="printer" size="sm" variant="ghost" />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2">
                        <p>Print Peripheral Reports</p>
                    </flux:tooltip.content>
                </flux:tooltip>
            </flux:heading>
            <span class="text-xl font-bold text-gray-700">
                {{ collect($this->peripheralSummary)->flatten(1)->sum('total') }}
            </span>
        </div>

        <!-- Toggle -->
        <button @click="toggle()"
            class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:underline flex items-center justify-between">
           <span x-text="open ? 'Hide statistics' : 'View statistics'"></span>
            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Summary Table -->
        <div x-show="open" x-transition class="overflow-x-auto p-4 border-t bg-gradient-to-r from-yellow-100 to-yellow-50 shadow-inner" x-data="stockTooltip()"
            x-init="init()">
            @foreach ($this->peripheralSummary as $type => $items)
                <h3 class="font-bold text-gray-900 dark:text-gray-200 mt-4">{{ $type }}</h3>
                <table class="w-full border-collapse mb-4">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-zinc-700 text-gray-500">
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-center">Quantity</th>
                            <th class="px-4 py-2 text-center">Available</th>
                            <th class="px-4 py-2 text-center">In Use</th>
                            <th class="px-4 py-2 text-center">Defective</th>
                            <th class="px-4 py-2 text-center">Maintenance</th>
                            <th class="px-4 py-2 text-center">Junk</th>
                            <th class="px-4 py-2 text-center">Salvage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
                                <td class="px-6 py-4">
                                    {{ $item['description'] }}
                                    @if ($item['available'] == 0)
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-semibold text-white bg-red-600 rounded-full">
                                            Out of stock
                                        </span>
                                    @elseif ($item['available'] < $lowStockThreshold)
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-500 bg-yellow-100 rounded-full">
                                            Low stock
                                        </span>
                                    @else
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-semibold text-green-500 bg-green-100 rounded-full">
                                            In stock
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">{{ $item['total'] }}</td>
                                 <td class="px-6 py-4 text-center stock-cell cursor-default"
                                    data-available="{{ $item['available'] }}"
                                    data-description="{{ $item['description'] }}" @mouseenter="show($event)"
                                    @mouseleave="hide()"> {{ $item['available'] }} </td>
                                <td class="px-4 py-2 text-center">{{ $item['in_use'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $item['defective'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $item['maintenance'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $item['junk'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $item['salvage'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>

    <!-- Search + Add -->
    <div class="flex flex-col md:flex-row md:justify-between gap-4 mb-4">
        <div class="flex-1">
            <flux:input type="text" placeholder="Search..." wire:model.live.debounce.300ms="query"
                icon="magnifying-glass" kbd="⌘K" />
        </div>
        <div>
            <flux:button variant="primary" color="blue" wire:click="openCreateModal">
                + Add Peripheral
            </flux:button>
        </div>
    </div>

    <!-- Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-200 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    
                    <th class="px-4 py-3">Serial Number</th>
                    <th class="px-4 py-3">Category</th>
                    {{-- <th class="px-4 py-3">Condition</th> --}}
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php
                    use App\Support\StatusConfig;
                   // $conditionColors = StatusConfig::conditions();
                    $statusColors = StatusConfig::statuses();
                @endphp

                @forelse($peripherals as $peripheral)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50 odd:bg-white even:bg-gray-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
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
                                            <button wire:click="openEditModal({{ $peripheral->id }})"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.pencil class="h-4 w-4" />
                                                <span>Edit</span>
                                            </button>
                                            <button wire:click="deletePeripheral({{ $peripheral->id }})"
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
                        <td colspan="6" class="text-center py-6 text-gray-500">No peripherals found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $peripherals->links() }}
    </div>

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
{{-- <script>
    function stockTooltip() {
        return {
            bubble: null,
            arrow: null,

            init() {
                // Create global tooltip bubble
                this.bubble = document.createElement('div');
                this.bubble.className = 'text-white px-3 py-1 rounded-r-xl shadow-lg absolute z-50 animate-fade-in';
                this.bubble.style.position = 'absolute';
                this.bubble.style.whiteSpace = 'nowrap';
                this.bubble.style.display = 'none';
                document.body.appendChild(this.bubble);

                // Create global arrow
                this.arrow = document.createElement('div');
                this.arrow.style.position = 'absolute';
                this.arrow.style.width = '0';
                this.arrow.style.height = '0';
                document.body.appendChild(this.arrow);
            },

            show(event) {
                const td = event.currentTarget;
                const available = parseInt(td.dataset.available, 10);
                const description = td.dataset.description;

                let bgColor = '#facc15';
                let statusText = 'Low stock';
                if (available === 0) {
                    bgColor = '#dc2626';
                    statusText = 'Out of stock';
                } else if (available >= 5) {
                    bgColor = '#16a34a';
                    statusText = 'In Stock';
                }

                // Bubble content & style
                this.bubble.innerText = `${description} — ${statusText} (${available} left)`;
                this.bubble.style.backgroundColor = bgColor;
                this.bubble.style.display = 'inline-block';
                this.bubble.style.top = (td.getBoundingClientRect().top + window.scrollY + 10) + 'px';
                this.bubble.style.left = (td.getBoundingClientRect().right + window.scrollX - 45) + 'px';

                // Arrow
                this.arrow.style.borderTop = '6px solid transparent';
                this.arrow.style.borderBottom = '6px solid transparent';
                this.arrow.style.borderRight = `6px solid ${bgColor}`;
                this.arrow.style.top = (td.getBoundingClientRect().top + window.scrollY + td.offsetHeight / 2 - 6) +
                    'px';
                this.arrow.style.left = (td.getBoundingClientRect().right + window.scrollX - 55) + 'px';
                this.arrow.style.display = 'block';

            },

            hide() {
                this.bubble.style.display = 'none';
                this.arrow.style.display = 'none';;
            }

        }
    }
</script> --}}
