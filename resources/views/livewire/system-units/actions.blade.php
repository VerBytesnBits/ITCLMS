<div>
    <!-- View -->
    <flux:tooltip hoverable>
        <flux:button wire:click="$dispatch('open-view-modal',[{{ $id }}])" icon="eye" variant="primary" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
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
        <flux:button wire:click="$dispatch('open-edit-modal',[{{ $id }}])" icon="pencil" variant="primary" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
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
        <flux:button x-data @click="$dispatch('open-qr-modal', { qr: '{{ asset($qr_code_path) }}', serial: '{{ $serial_number }}' })" icon="qr-code" variant="primary" class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 
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
        <flux:button x-data @click="$dispatch('openReportIssue', { systemUnitId: {{ $id }} })" icon="triangle-alert" variant="primary" class="text-white bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 
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
    <flux:button x-data @click="$dispatch('confirm-delete-system-unit', { id: {{ $id }} })" icon="trash" variant="primary" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 
           hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
           dark:focus:ring-red-800 shadow-lg shadow-red-500/50 
           dark:shadow-lg dark:shadow-red-800/80 
           font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
        {{-- <flux:icon.trash class="w-5 h-5 text-white" /> --}}
    </flux:button>
</div>
