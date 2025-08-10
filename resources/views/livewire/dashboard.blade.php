<div class="flex flex-col h-full w-full flex-1 gap-6 rounded-xl p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

        {{-- Chairman cards --}}
        @role('chairman')
            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Total System Units</h3>
                    <p class="text-3xl font-bold">150</p>
                </div>
                <button wire:click="openModal('total_units')" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Operational Units</h3>
                    <p class="text-3xl font-bold">130</p>
                </div>
                <button wire:click="openModal('operational')" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Units Under Maintenance</h3>
                    <p class="text-3xl font-bold">12</p>
                </div>
                <button wire:click="openModal('under_maintenance')" class="mt-4 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Needs Repair</h3>
                    <p class="text-3xl font-bold">7</p>
                </div>
                <button wire:click="openModal('needs_repair')" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 self-start">View</button>
            </div>
        @endrole

        {{-- Lab In-Charge cards --}}
        @role('lab_incharge')
            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Units You Manage</h3>
                    <p class="text-3xl font-bold">85</p>
                </div>
                <button wire:click="openModal('units_you_manage')" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Non-Operational Units</h3>
                    <p class="text-3xl font-bold">15</p>
                </div>
                <button wire:click="openModal('non_operational')" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Units Under Maintenance</h3>
                    <p class="text-3xl font-bold">5</p>
                </div>
                <button wire:click="openModal('under_maintenance')" class="mt-4 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Needs Repair</h3>
                    <p class="text-3xl font-bold">3</p>
                </div>
                <button wire:click="openModal('needs_repair')" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 self-start">View</button>
            </div>
        @endrole

        {{-- Lab Technician cards --}}
        @role('lab_technician')
            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Units Under Maintenance</h3>
                    <p class="text-3xl font-bold">9</p>
                </div>
                <button wire:click="openModal('under_maintenance')" class="mt-4 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Needs Repair</h3>
                    <p class="text-3xl font-bold">4</p>
                </div>
                <button wire:click="openModal('needs_repair')" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Latest Notes</h3>
                    <p class="text-3xl font-bold">10</p>
                </div>
                <button wire:click="openModal('latest_notes')" class="mt-4 px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 self-start">View</button>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6 shadow flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Latest Updates</h3>
                    <p class="text-3xl font-bold">8</p>
                </div>
                <button wire:click="openModal('latest_updates')" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 self-start">View</button>
            </div>
        @endrole
    </div>

    <div
        class="relative flex-1 overflow-auto rounded-xl border p-6 shadow bg-white dark:bg-zinc-900 border-neutral-200 dark:border-neutral-700
               max-h-[60vh] sm:max-h-[70vh] md:max-h-[80vh] mt-6">
        <h2 class="text-xl font-semibold mb-4">Recent Activities</h2>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700 overflow-auto max-h-full">
            {{-- Dummy activities --}}
            <li class="py-2">
                <p class="text-sm">
                    <strong>John Doe</strong> created a new system unit.
                </p>
                <span class="text-xs text-gray-500 dark:text-gray-400">5 minutes ago</span>
            </li>
            <li class="py-2">
                <p class="text-sm">
                    <strong>Jane Smith</strong> reported a unit under maintenance.
                </p>
                <span class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</span>
            </li>
            <li class="py-2">
                <p class="text-sm">
                    <strong>Chairman</strong> marked a unit as needing repair.
                </p>
                <span class="text-xs text-gray-500 dark:text-gray-400">Yesterday</span>
            </li>
        </ul>
    </div>

    {{-- Modal --}}
    <x-modal name="unitDetailsModal" maxWidth="4xl" wire:model="showModal">
        <div class="p-4">
            <h3 class="text-xl font-semibold mb-4">Unit Details</h3>

            @if($modalType === 'total_units')
                <p>This is a dummy list of all system units.</p>
                <ul class="list-disc list-inside">
                    <li>System Unit 1</li>
                    <li>System Unit 2</li>
                    <li>System Unit 3</li>
                </ul>
            @elseif($modalType === 'under_maintenance')
                <p>This is a dummy list of units currently under maintenance.</p>
                <ul class="list-disc list-inside">
                    <li>Unit A - PSU issue</li>
                    <li>Unit B - RAM failure</li>
                    <li>Unit C - Hard drive replacement</li>
                </ul>
            @elseif($modalType === 'needs_repair')
                <p>This is a dummy list of units needing repair and their components/peripherals.</p>
                <ul class="list-disc list-inside">
                    <li>Unit X
                        <ul class="list-disc list-inside ml-5">
                            <li>Monitor</li>
                            <li>Keyboard</li>
                        </ul>
                    </li>
                    <li>Unit Y
                        <ul class="list-disc list-inside ml-5">
                            <li>Power Supply</li>
                        </ul>
                    </li>
                </ul>
            @elseif($modalType === 'units_you_manage')
                <p>This is a dummy list of units you manage.</p>
                <ul class="list-disc list-inside">
                    <li>Managed Unit 1</li>
                    <li>Managed Unit 2</li>
                </ul>
            @elseif($modalType === 'operational')
                <p>This is a dummy list of operational units.</p>
                <ul class="list-disc list-inside">
                    <li>Unit 101</li>
                    <li>Unit 102</li>
                    <li>Unit 103</li>
                </ul>
            @elseif($modalType === 'non_operational')
                <p>This is a dummy list of non-operational units.</p>
                <ul class="list-disc list-inside">
                    <li>Unit 201</li>
                    <li>Unit 202</li>
                </ul>
            @elseif($modalType === 'latest_notes')
                <p>This is a dummy list of latest notes.</p>
                <ul class="list-disc list-inside">
                    <li>Note 1: Replace RAM in Unit 15</li>
                    <li>Note 2: Update software on Unit 22</li>
                    <li>Note 3: Scheduled maintenance for Unit 3</li>
                </ul>
            @elseif($modalType === 'latest_updates')
                <p>This is a dummy list of latest updates.</p>
                <ul class="list-disc list-inside">
                    <li>Update 1: Fixed overheating issue on Unit 5</li>
                    <li>Update 2: Installed new PSU in Unit 9</li>
                    <li>Update 3: Patched security vulnerability in Unit 12</li>
                </ul>
            @else
                <p>Select a card to view details.</p>
            @endif

            <div class="mt-4 flex justify-end">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Close</button>
            </div>
        </div>
    </x-modal>
</div>
