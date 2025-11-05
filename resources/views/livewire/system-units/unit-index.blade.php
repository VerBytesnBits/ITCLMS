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
                    <button wire:navigate href="/reports/unit"
                        class="flex items-center justify-center w-10 h-10 rounded-full 
               bg-gray-400 hover:bg-gray-700 text-white shadow-md transition duration-200">
                        <flux:icon.printer class="w-5 h-5" />
                    </button>

                    <!-- Tooltip -->
                    <div
                        class="absolute left-[-7rem] top-1/2 -translate-y-1/2 
               bg-gray-800 text-white text-xs rounded px-2 py-1 
               opacity-0 group-hover:opacity-100 transition duration-200 
               pointer-events-none shadow-lg whitespace-nowrap">
                        Print Unit Reports
                    </div>
                </div>

                <livewire:system-units.decommissioned-units />
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

                <flux:button icon="plus" variant="primary" color="green" wire:click="create"
                    class="w-full sm:w-auto rounded-xl shadow-md hover:shadow-lg transition">
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

                        <!-- Status Badge -->
                        <td class="px-4 py-3 text-center">
                            <span
                                class="px-3 py-1 text-xs rounded-full font-semibold 
                                       {{ $statusColors[$unit->status] ?? 'bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-200' }}">
                                {{ $unit->status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-3 text-center space-x-2">

                            <div x-data="{ open: false }" class="relative inline-flex w-full sm:w-auto">
                                <!-- View -->
                                <button wire:click="view({{ $unit->id }})"
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
                                                let dropdownHeight = $el.offsetHeight || 125; // fallback height
                                                let spaceBelow = window.innerHeight - btn.bottom;
                                                let spaceAbove = btn.top;
                                        
                                                $el.style.position = 'absolute';
                                                $el.style.left = (btn.left + window.scrollX) + 'px';
                                        
                                                if (spaceBelow < dropdownHeight && spaceAbove > dropdownHeight) {
                                                    // place above
                                                    $el.style.top = (btn.top + window.scrollY - dropdownHeight) + 'px';
                                                } else {
                                                    // place below
                                                    $el.style.top = (btn.bottom + window.scrollY) + 'px';
                                                }
                                            }
                                        })"
                                        class="z-50 mt-1 w-30 rounded-md shadow-lg bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <!-- actions -->
                                            <button wire:click="edit({{ $unit->id }})" @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.pencil class="h-4 w-4" />
                                                <span>Edit</span>
                                            </button>
                                            {{-- <button wire:click="report({{ $unit->id }})" @click="open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.triangle-alert class="h-4 w-4" />
                                                <span>Report</span>
                                            </button> --}}
                                            {{-- <button x-data
                                                @click="$dispatch('open-modal', { component: 'issues.report-issue', { id: {{ $unit->id }} }); open = false"
                                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                                Report Issue
                                            </button> --}}
                                            <button x-data
                                                @click="$dispatch('openReportIssue', { systemUnitId: {{ $unit->id }} }); open = false"
                                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                <flux:icon.triangle-alert class="h-4 w-4" />
                                                Report
                                            </button>

                                            <button x-data
                                                @click="$dispatch('confirm-delete-system-unit', { id: {{ $unit->id }} }); open = false"
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
