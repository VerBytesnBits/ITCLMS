@php
    use App\Support\StatusConfig;
    $statusColors = StatusConfig::statuses();
@endphp

<div class="space-y-6">
    <!-- Heading -->
    <livewire:dashboard-heading title="Computer Units" subtitle="Manage and monitor all Computer units"
        icon="computer-desktop" gradient-from-color="#3b82f6" gradient-to-color="#7c3aed" icon-color="text-blue-500" />

    <!-- Right: Search + Filters + Button -->

    <div
        class="relative bg-white dark:bg-zinc-800 rounded-2xl shadow-md border border-zinc-200 dark:border-zinc-700 overflow-hidden outline-2 outline-offset-2 outline-blue-500/50">
        {{-- <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div> --}}
        <!-- content -->

        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 flex items-center justify-between">
            <!-- Left side -->
            <div>
                <flux:heading size="lg" level="1"
                    class="text-lg flex items-center gap-2 text-zinc-600 dark:text-zinc-50">
                    Controls
                </flux:heading>
                <flux:text class="text-xs">Search, filter, and add computer units</flux:text>
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-3">
                <!-- Printer Button with matching tooltip style -->
                <div class="relative group inline-block">
                    <flux:tooltip hoverable>
                        <flux:button :href="route('units.report')" wire:navigate icon="printer" variant="primary"
                            class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 
           dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 
           dark:shadow-lg dark:shadow-gray-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">

                            Unit Reports
                        </flux:button>
                        <flux:tooltip.content class="max-w-[20rem] space-y-2">
                            <p>Unit Reports</p>
                        </flux:tooltip.content>
                    </flux:tooltip>



                </div>
                <flux:tooltip hoverable>
                    <livewire:system-units.decommissioned-units />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2">
                        <p>Decommisioned Unit</p>
                    </flux:tooltip.content>
                </flux:tooltip>

            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6 space-y-6">
            <!-- Stats Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Operational -->
                <div
                    class="flex items-center justify-between p-4 rounded-2xl shadow-sm 
                bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/40 
                hover:shadow-md transition">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span> Operational
                    </span>
                    <span class="text-xl font-bold text-green-700 dark:text-green-300">{{ $operationalCount }}</span>
                </div>
                <!-- Non-Operational -->
                <div
                    class="flex items-center justify-between p-4 rounded-2xl shadow-sm 
                bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/40 
                hover:shadow-md transition">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span> Non-Operational
                    </span>
                    <span class="text-xl font-bold text-red-700 dark:text-red-300">{{ $nonOperationalCount }}</span>
                </div>
            </div>

            <!-- Filters Row -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <flux:input wire:model.live="search" placeholder="Search units..." icon="magnifying-glass"
                    class="flex-[3] w-full min-w-[200px]" />

                <flux:select wire:model.live="selectedRoom" class="flex-1 w-full min-w-[160px]">
                    <option value="">All Rooms</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="statusFilter" class="flex-1 w-full min-w-[160px]">
                    <option value="">All Status</option>
                    <option value="Operational">Operational</option>
                    <option value="Non-operational">Non-Operational</option>
                    <option value="Needs Repair">Needs Repair</option>
                </flux:select>

                <flux:button icon="circle-plus" variant="primary" color="green" wire:click="create"
                    class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
                    Add Unit
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Units Table Card -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-blue-500 text-xs uppercase text-zinc-100">
                <tr>
                    {{-- <th class="px-6 py-4 text-left font-semibold">#</th> --}}

                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3 text-center">Room</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                @forelse($units as $unit)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50 odd:bg-white even:bg-zinc-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
                        {{-- <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">{{ $unit->id }}</td> --}}
                        <td class="px-4 py-3 font-medium text-zinc-800 dark:text-white">{{ $unit->name }}</td>
                        <td class="px-6 py-4 text-center">{{ $unit->room?->name ?? 'N/A' }}</td>

                        <td class="px-4 py-3 text-center">
                            <span
                                class="px-3 py-1 text-base rounded-full font-semibold
               {{ $statusColors[$unit->status] ?? 'bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-200' }}">
                                {{ $unit->status }}
                            </span>

                            @php
                                $check = $unit->checkOperationalStatus();
                                $missing = $check['missing'];
                            @endphp

                            @if ($check['status'] === 'Operational')
                                <span class="text-green-600 font-semibold text-sm block mt-1"></span>
                            @else
                                <div class="flex flex-wrap gap-1 justify-center mt-1">
                                    @foreach ($missing['components'] as $item)
                                        <span
                                            class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs flex items-center gap-1">
                                            <flux:icon.triangle-alert variant="micro"/>
                                            {{ $item }}
                                        </span>
                                    @endforeach

                                    @foreach ($missing['peripherals'] as $item)
                                        <span
                                            class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs flex items-center gap-1">
                                            <flux:icon.triangle-alert variant="micro"/>
                                            {{ $item }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center justify-center">

                            <!-- DESKTOP BUTTONS (md and up) -->
                            <div class="hidden md:flex text-center justify-center space-x-1">

                                <!-- View -->
                                <flux:tooltip hoverable>
                                    <flux:button wire:click="view({{ $unit->id }})" icon="eye"
                                        variant="primary"
                                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 
           dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
           dark:shadow-lg dark:shadow-blue-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                        {{-- <flux:icon.eye class="w-5 h-5 text-white" /> --}}
                                    </flux:button>
                                    <flux:tooltip.content class="max-w-[20rem] ">
                                        <p>View</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>

                                <flux:tooltip hoverable>
                                    <!-- Edit -->
                                    <flux:button wire:click="edit({{ $unit->id }})" icon="pencil"
                                        variant="primary"
                                        class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 
           dark:focus:ring-green-800 shadow-lg shadow-green-500/50 
           dark:shadow-lg dark:shadow-green-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                        {{-- <flux:icon.pencil class="w-5 h-5 text-white" /> --}}
                                    </flux:button>
                                    <flux:tooltip.content class="max-w-[20rem] ">
                                        <p>Modify</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>

                                <flux:tooltip hoverable>
                                    <!-- View QR -->
                                    <flux:button x-data
                                        @click="$dispatch('open-qr-modal', { qr: '{{ asset($unit->qr_code_path) }}', serial: '{{ $unit->serial_number }}' })"
                                        icon="qr-code" variant="primary"
                                        class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 
           dark:focus:ring-cyan-800 shadow-lg shadow-cyan-500/50 
           dark:shadow-lg dark:shadow-cyan-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                        {{-- <flux:icon.qr-code class="w-5 h-5 text-white" /> --}}
                                    </flux:button>
                                    <flux:tooltip.content class="max-w-[20rem] ">
                                        <p>View QR</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>

                                <flux:tooltip hoverable>
                                    <!-- Report Issue -->
                                    <flux:button x-data
                                        @click="$dispatch('openReportIssue', { systemUnitId: {{ $unit->id }} })"
                                        icon="triangle-alert" variant="primary"
                                        class="text-white bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-yellow-300 
           dark:focus:ring-yellow-800 shadow-lg shadow-yellow-500/50 
           dark:shadow-lg dark:shadow-yellow-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                        {{-- <flux:icon.triangle-alert class="w-5 h-5 text-white" /> --}}
                                    </flux:button>
                                    <flux:tooltip.content class="max-w-[20rem] ">
                                        <p>Report Issue</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>


                                <!-- Delete -->
                                <flux:button x-data
                                    @click="$dispatch('confirm-delete-system-unit', { id: {{ $unit->id }} })"
                                    icon="trash" variant="primary"
                                    class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
           dark:focus:ring-red-800 shadow-lg shadow-red-500/50 
           dark:shadow-lg dark:shadow-red-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
                                    {{-- <flux:icon.trash class="w-5 h-5 text-white" /> --}}
                                </flux:button>


                            </div>


                            <div class="md:hidden" x-data="{ open: false }">
                                <button @click="open = !open" x-ref="toggleBtn" type="button"
                                    class="inline-flex items-center justify-center px-3 py-2 border rounded-md bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-200">
                                    Actions
                                    <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <template x-teleport="body">
                                    <div x-show="open" x-transition @click.away="open = false" x-cloak
                                        x-init="$watch('open', value => {
                                            if (value) {
                                                let btn = $refs.toggleBtn.getBoundingClientRect();
                                                let dropdownHeight = $el.offsetHeight || 270;
                                                let spaceBelow = window.innerHeight - btn.bottom;
                                                let spaceAbove = btn.top;
                                        
                                                $el.style.position = 'absolute';
                                                $el.style.left = (btn.left + window.scrollX) + 'px';
                                        
                                                if (spaceBelow < dropdownHeight && spaceAbove > dropdownHeight) {
                                                    $el.style.top = (btn.top + window.scrollY - dropdownHeight) + 'px';
                                                } else {
                                                    $el.style.top = (btn.bottom + window.scrollY) + 'px';
                                                }
                                            }
                                        })"
                                        class="z-50 mt-1 w-40 rounded-md shadow-lg bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5">

                                        <div class="py-1">
                                            <!-- View -->
                                            <button wire:click="view({{ $unit->id }})"
                                                class="flex items-center gap-2 w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.eye class="h-4 w-4" /> View
                                            </button>
                                            <button wire:click="edit({{ $unit->id }})" @click="open = false"
                                                class="flex items-center gap-2 w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.pencil class="h-4 w-4" /> Edit
                                            </button>

                                            <button
                                                @click="$dispatch('open-qr-modal', { qr: '{{ asset($unit->qr_code_path) }}', serial: '{{ $unit->serial_number }}' }); open = false"
                                                class="flex items-center gap-2 w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.qr-code class="h-4 w-4" /> View QR
                                            </button>

                                            <button
                                                @click="$dispatch('openReportIssue', { systemUnitId: {{ $unit->id }} }); open = false"
                                                class="flex items-center gap-2 w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.triangle-alert class="h-4 w-4" /> Report
                                            </button>

                                            <button
                                                @click="$dispatch('confirm-delete-system-unit', { id: {{ $unit->id }} }); open = false"
                                                class="flex items-center gap-2 w-full px-4 py-2 text-left text-red-600 hover:bg-red-50 dark:hover:bg-red-700/30">
                                                <flux:icon.trash class="h-4 w-4" /> Delete
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>



                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400 font-medium text-sm">
                            No Units Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{ $units->links() }}
    <livewire:issues.report-issue />
    <livewire:system-units.delete-modal />

    <!-- Modal Component -->
    <div x-data="{ open: false, qr: '', serial: '' }"
        x-on:open-qr-modal.window="open = true; qr = $event.detail.qr; serial = $event.detail.serial" x-show="open"
        x-cloak class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 px-2 sm:px-4">
        <div x-show="open" @click.away="open = false" x-transition
            class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center relative">
            <!-- Close Button -->
            <button @click="open = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700">
                ✕
            </button>

            <!-- QR Code Image -->
            <template x-if="qr">
                <img :src="qr" alt="QR Code" class="h-48 w-48 mx-auto mb-3">
            </template>

            <!-- Serial Number -->
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                Serial Number
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="serial"></p>

            <!-- Download Button -->
            <a :href="qr" download
                class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Download QR
            </a>
        </div>
    </div>

    <!-- Modals -->
    @if ($showModal)
        <livewire:system-units.unit-form :unitId="$selectedUnit?->id" :mode="$modalMode" />
    @endif

    @if ($showAssignModal && $assignUnitId)
        <livewire:system-units.unit-assign-parts :unitId="$assignUnitId" />
    @endif

    @if ($modalMode === 'view' && $unitId)
        <livewire:system-units.unit-view :unitId="$unitId" wire:key="unit-view-{{ $unitId }}" />
    @endif

</div>
