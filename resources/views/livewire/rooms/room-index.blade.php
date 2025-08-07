<div class="p-4">
    @can('create.laboratories')
        <button wire:click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer">
            Create Room
        </button>
    @endcan

    <div class="mt-6 overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Lab In-Charge</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-2">{{ $room->id }}</td>
                        <td class="px-6 py-2">{{ $room->name }}</td>
                        <td class="px-6 py-2">{{ $room->labInCharge?->name ?? '—' }}</td>
                        <td class="px-6 py-2">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $room->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-2">
                            <button class="text-yellow-500 px-2 cursor-pointer">Manage</button>
                            @can('update.laboratories')
                                <button wire:click="openEditModal({{ $room->id }})"
                                    class="text-blue-500 px-2 cursor-pointer">Edit</button>
                            @endcan
                            @can('delete.laboratories')
                                <button wire:click="deleteRoom({{ $room->id }})"
                                    class="text-red-500 px-2 cursor-pointer">Delete</button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Room Modal --}}
    @if ($modal === 'create' || $modal === 'edit')
        <livewire:rooms.room-form :room-id="$id" />
    @endif
</div>
