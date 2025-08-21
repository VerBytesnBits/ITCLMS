<div class="flex flex-col h-full w-full flex-1 gap-6 rounded-xl p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

        <x-dashboard.card 
            title="Total System Units" 
            value="{{ $totalUnits }}" 
            color="blue" 
            action="openModal('total_units')" />

        <x-dashboard.card 
            title="Operational Units" 
            value="{{ $operationalUnits }}" 
            color="green" 
            action="openModal('operational')" />

        <x-dashboard.card 
            title="Non-Operational Units" 
            value="{{ $nonOperationalUnits }}" 
            color="red" 
            action="openModal('non_operational')" />
    </div>

    <div class="relative flex-1 overflow-auto rounded-xl border p-6 shadow bg-white dark:bg-zinc-900 
                border-neutral-200 dark:border-neutral-700 max-h-[70vh] mt-6">
        <h2 class="text-xl font-semibold mb-4">Recent Activities</h2>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 overflow-auto max-h-full">
            <li class="py-2">
                <p class="text-sm"><strong>John Doe</strong> created a new system unit.</p>
                <span class="text-xs text-gray-500 dark:text-gray-400">5 minutes ago</span>
            </li>
            <li class="py-2">
                <p class="text-sm"><strong>Jane Smith</strong> reported a unit as non-operational.</p>
                <span class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</span>
            </li>
        </ul>
    </div>

    {{-- Modal --}}
    <x-modal name="unitDetailsModal" maxWidth="4xl" wire:model="showModal">
        <div class="p-4">
            <h3 class="text-xl font-semibold mb-4">Unit Details</h3>

            @switch($modalType)
                @case('total_units')
                    <p>Showing all system units…</p>
                    @break
                @case('operational')
                    <p>Showing operational units…</p>
                    @break
                @case('non_operational')
                    <p>Showing non-operational units…</p>
                    @break
                @default
                    <p>Select a card to view details.</p>
            @endswitch

            <div class="mt-4 flex justify-end">
                <button wire:click="$set('showModal', false)" 
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Close
                </button>
            </div>
        </div>
    </x-modal>
</div>
