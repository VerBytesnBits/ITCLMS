<div>

    <livewire:dashboard-heading title="Rooms" subtitle="Manage all laboratories/rooms" icon="home"
        gradient-from-color="#7d4173" gradient-to-color="#cc6166" icon-color="text-red-300" />

    @if ($rooms->count())
        <div class="flex justify-end items-center mb-6">
            <flux:button variant="primary" color="green" wire:click="openCreateModal"
                class="px-5 py-2 rounded-full shadow-md hover:shadow-lg transition">
                + Add Room
            </flux:button>
        </div>
    @endif


    <!-- Modern Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($rooms as $room)
            <!-- Room Card -->
            <div
                class="relative bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 
           rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 
           flex flex-col overflow-hidden">

                <!-- Header -->
                <div
                    class="p-5 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-start bg-gradient-to-r from-green-500 to-emerald-600">

                    <h3 class="text-lg font-semibold text-white dark:text-zinc-100 flex flex-row gap-3">
                        <flux:icon.home class="text-zinc-100" />{{ $room->name }}
                    </h3>
                    @php
                        $statusColors = [
                            'Available' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                            'In Use' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                            'Maintenance' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'Closed' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                        ];
                    @endphp
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$room->status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' }}">
                        {{ $room->status ?? '—' }}
                    </span>
                </div>

                <!-- Body -->
                <div class="p-5 flex-1 space-y-5">
                    <!-- Lab In-Charge -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <flux:icon.user class="w-4 h-4" /> Lab In-Charge
                            </p>
                            @role('chairman')
                                <button wire:click="openAssignLabIncharge({{ $room->id }})"
                                    class="group relative flex items-center justify-center w-7 h-7 rounded-full 
                       bg-purple-500 hover:bg-purple-600 text-white transition">
                                    <flux:icon.circle-plus class="w-4 h-4" />

                                    <!-- Tooltip -->
                                    <span
                                        class="absolute right-9 top-1/2 -translate-y-1/2 whitespace-nowrap
                           bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0
                           group-hover:opacity-100 transition duration-200 pointer-events-none shadow-lg">
                                        Assign Lab In-Charge
                                    </span>
                                </button>
                            @endrole
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @forelse($room->users->where('pivot.role_in_room', 'lab_incharge') as $incharge)
                                <span
                                    class="flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-medium
                                    bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300
                                    group relative">

                                    {{ $incharge->name }}

                                    <button wire:click="removeIncharge({{ $room->id }}, {{ $incharge->id }})"
                                        class="group-hover:opacity-100 transition-opacity duration-200
                                        text-gray-500 hover:text-red-700 dark:hover:text-red-400"
                                        title="Unassign Lab In-Charge">
                                        <flux:icon.x class="w-3.5 h-3.5" />
                                    </button>
                                </span>
                            @empty
                                <span class="text-gray-400 text-sm">—</span>
                            @endforelse

                        </div>
                    </div>

                    <!-- Technicians -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <flux:icon.wrench class="w-4 h-4" /> Technicians
                            </p>
                            @role(['lab_incharge', 'chairman'])
                                <button wire:click="openAssignTechnician({{ $room->id }})"
                                    class="group relative flex items-center justify-center w-7 h-7 rounded-full 
                       bg-indigo-500 hover:bg-indigo-600 text-white transition">
                                    <flux:icon.circle-plus class="w-4 h-4" />

                                    <!-- Tooltip -->
                                    <span
                                        class="absolute right-9 top-1/2 -translate-y-1/2 whitespace-nowrap
                           bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0
                           group-hover:opacity-100 transition duration-200 pointer-events-none shadow-lg">
                                        Assign Technician
                                    </span>
                                </button>
                            @endrole
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @forelse($room->users->where('pivot.role_in_room', 'lab_technician') as $tech)
                                <span
                                    class="flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-medium
                                    bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300
                                        group relative">

                                    {{ $tech->name }}

                                    <button wire:click="removeTechnician({{ $room->id }}, {{ $tech->id }})"
                                        class="group-hover:opacity-100 transition-opacity duration-200
                                           text-gray-500 hover:text-red-700 dark:hover:text-red-400"
                                        title="Unassign Lab-Technician">
                                        <flux:icon.x class="w-3.5 h-3.5 " />
                                    </button>
                                </span>
                            @empty
                                <span class="text-gray-400 text-sm">—</span>
                            @endforelse

                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div
                    class="p-4 border-t border-zinc-200 dark:border-zinc-700 
           flex items-center justify-end bg-gradient-to-r 
           from-zinc-50 via-zinc-100 to-zinc-50 
           dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900
           rounded-b-2xl">

                    <!-- Left side: info or label -->
                    {{-- <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        Actions
                    </p> --}}

                    <!-- Right side: action buttons -->
                    <div class="flex items-center gap-2">
                        <button wire:click="openEditModal({{ $room->id }})"
                            class="flex items-center justify-center gap-1 px-3 py-1.5 text-xs font-medium 
                   rounded-md border border-yellow-400/40 
                   text-yellow-600 hover:bg-yellow-100 
                   dark:border-yellow-400/30 dark:hover:bg-yellow-900/30 
                   transition-all">
                            <flux:icon.pencil class="w-4 h-4" />
                            {{-- <span>Edit</span> --}}
                        </button>

                        <button wire:click.prevent="confirmDeleteRoom({{ $room->id }})"
                            class="flex items-center justify-center gap-1 px-3 py-1.5 text-xs font-medium 
                   rounded-md border border-red-400/40 
                   text-red-600 hover:bg-red-100 
                   dark:border-red-400/30 dark:hover:bg-red-900/30 
                   transition-all">
                            <flux:icon.trash class="w-4 h-4" />
                            {{-- <span>Delete</span> --}}
                        </button>
                      

                    </div>
                </div>

            </div>

        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <flux:icon.home class="w-12 h-12 text-gray-400 mb-4" />
                <p class="text-gray-500 dark:text-gray-400 text-lg">No rooms found.</p>
                <flux:button variant="primary" color="green" wire:click="openCreateModal"
                    class="px-5 py-2 rounded-full shadow-md hover:shadow-lg transition">
                    + Add Room
                </flux:button>
            </div>
        @endforelse
    </div>


    <!-- Conditional Modals -->
    @if ($modal === 'create' || $modal === 'edit')
        <livewire:rooms.room-form :room-id="$id" :key="'room-' . ($id ?? 'create')" />
    @endif

    @if ($modal === 'assign-lab-incharge')
        <livewire:rooms.assign-lab-incharge :room-id="$id" :key="'assign-lab-' . $id" />
    @endif

    @if ($modal === 'assign-technician')
        <livewire:rooms.assign-technician :room-id="$id" :key="'assign-tech-' . $id" />
    @endif

</div>
