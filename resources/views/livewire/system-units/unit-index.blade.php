<div class="space-y-6">
    <!-- Heading -->
    <div class="flex justify-between max-sm:flex-col">
        <div>
            <flux:heading size="xl" level="1" class="text-2xl! font-bold! text-zinc-500 dark:text-zinc-50 flex items-center gap-2 leading-tight italic"> Computer Units</flux:heading>
            <flux:subheading size="lg" class="italic">Manage and monitor all Computer units</flux:subheading>
        </div>

        <div class="w-full sm:w-auto ">
            <flux:button icon="circle-plus" variant="primary" color="green" wire:click="create" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
                Add Peripheral
            </flux:button>
        </div>
    </div>
    <flux:separator variant="subtle" />
  

    <div class="relative bg-white dark:bg-zinc-800 rounded-2xl shadow-md border border-zinc-200 dark:border-zinc-700 overflow-hidden outline-2 outline-offset-2 outline-blue-500/50">
        {{-- <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div> --}}
        <!-- content -->

        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 flex items-center justify-between">
            <!-- Left side -->
            <div>
                <flux:heading size="lg" level="1" class="text-lg flex items-center gap-2 text-zinc-600 dark:text-zinc-50">
                    Monitoring Board
                </flux:heading>
                <flux:text class="text-xs">Monitor and generate reports.</flux:text>
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-3">
                <!-- Printer Button with matching tooltip style -->
                <div class="relative group inline-block">
                    <flux:tooltip hoverable>
                        <flux:button :href="route('units.report')" wire:navigate icon="printer" variant="primary" class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 
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
                <div class="flex items-center justify-between p-4 rounded-2xl shadow-sm 
                bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/40 
                hover:shadow-md transition">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span> Operational
                    </span>
                    <span class="text-xl font-bold text-green-700 dark:text-green-300">{{ $operationalCount }}</span>
                </div>
                <!-- Non-Operational -->
                <div class="flex items-center justify-between p-4 rounded-2xl shadow-sm 
                bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/40 
                hover:shadow-md transition">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span> Non-Operational
                    </span>
                    <span class="text-xl font-bold text-red-700 dark:text-red-300">{{ $nonOperationalCount }}</span>
                </div>
            </div>


        </div>
    </div>

    <div>
        <livewire:unit-table />
    </div>
    <livewire:issues.report-issue />
    <livewire:system-units.delete-modal />

    <!-- Modal Component -->
    <div x-data="{ open: false, qr: '', serial: '' }" x-on:open-qr-modal.window="open = true; qr = $event.detail.qr; serial = $event.detail.serial" x-show="open" x-cloak class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 px-2 sm:px-4">
        <div x-show="open" @click.away="open = false" x-transition class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center relative">
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
            <a :href="qr" download class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
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
