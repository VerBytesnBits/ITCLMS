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
                    
                    <h3 class="text-lg font-semibold text-white dark:text-zinc-100 flex flex-row gap-3"> <flux:icon.home class="text-zinc-100" />{{ $room->name }}</h3>
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
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <flux:icon.user class="w-4 h-4" /> Lab In-Charge
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($room->users->where('pivot.role_in_room', 'lab_incharge') as $incharge)
                                <span
                                    class="px-3 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                    {{ $incharge->name }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-sm">—</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Technicians -->
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <flux:icon.wrench class="w-4 h-4" /> Technicians
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($room->users->where('pivot.role_in_room', 'lab_technician') as $tech)
                                <span
                                    class="px-3 py-1 rounded-lg text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                    {{ $tech->name }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-sm">—</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="p-4 border-t border-zinc-200 dark:border-zinc-700 flex flex-wrap gap-2 justify-evenly bg-zinc-50 dark:bg-zinc-900/50">
                    @role('chairman')
                        <button wire:click="openAssignLabIncharge({{ $room->id }})"
                            class="px-4 py-1.5 rounded-full text-xs font-medium bg-purple-500 hover:bg-purple-600 text-white shadow transition">
                            In-Charge
                        </button>
                    @endrole

                    @role(['lab_incharge', 'chairman'])
                        <button wire:click="openAssignTechnician({{ $room->id }})"
                            class="px-4 py-1.5 rounded-full text-xs font-medium bg-indigo-500 hover:bg-indigo-600 text-white shadow transition">
                            Technician
                        </button>
                    @endrole

                    <button wire:click="openEditModal({{ $room->id }})"
                        class="px-4 py-1.5 rounded-full text-xs font-medium bg-yellow-500 hover:bg-yellow-600 text-white shadow transition">
                        Edit
                    </button>

                    <button wire:click="deleteRoom({{ $room->id }})"
                        class="px-4 py-1.5 rounded-full text-xs font-medium bg-red-500 hover:bg-red-600 text-white shadow transition">
                        Delete
                    </button>
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
