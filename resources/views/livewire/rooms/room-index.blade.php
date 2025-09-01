<div class="p-4">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Rooms</h1>
        <button wire:click="openCreateModal"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">
            + Create Room
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow rounded-xl">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-zinc-800 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Lab In-Charge</th>
                    <th class="px-6 py-3">Technicians</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach($rooms as $room)
                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                    <!-- Name -->
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                        {{ $room->name }}
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'Available' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                'In Use' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                'Maintenance' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                'Closed' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$room->status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ $room->status ?? '—' }}
                        </span>
                    </td>

                    <!-- Lab In-Charge -->
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        @forelse($room->users->where('pivot.role_in_room', 'lab_incharge') as $incharge)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                {{ $incharge->name }}
                            </span>
                        @empty
                            <span class="text-gray-400">—</span>
                        @endforelse
                    </td>

                    <!-- Technicians -->
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        @forelse($room->users->where('pivot.role_in_room', 'lab_technician') as $tech)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 mr-1">
                                {{ $tech->name }}
                            </span>
                        @empty
                            <span class="text-gray-400">—</span>
                        @endforelse
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 text-right space-x-2">
                        @role('chairman')
                        <button wire:click="openAssignLabIncharge({{ $room->id }})"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                            In-Charge
                        </button>
                        @endrole

                        @role(['lab_incharge','chairman'])
                        <button wire:click="openAssignTechnician({{ $room->id }})"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                            Technician
                        </button>
                        @endrole

                        <button wire:click="openEditModal({{ $room->id }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                            Edit
                        </button>

                        <button wire:click="deleteRoom({{ $room->id }})"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Conditional Modals -->
    @if($modal === 'create' || $modal === 'edit')
        <livewire:rooms.room-form :room-id="$id" :key="'room-' . ($id ?? 'create')" />
    @endif

    @if($modal === 'assign-lab-incharge')
        <livewire:rooms.assign-lab-incharge :room-id="$id" :key="'assign-lab-' . $id" />
    @endif

    @if($modal === 'assign-technician')
        <livewire:rooms.assign-technician :room-id="$id" :key="'assign-tech-' . $id" />
    @endif

</div>
