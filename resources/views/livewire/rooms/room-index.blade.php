<div class="p-4">
    @can('create.laboratories')
        <button wire:click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer dark:bg-blue-600">
            Create Room
        </button>
    @endcan

    <div class="mt-6 overflow-x-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach ($rooms as $room)
                <div class="rounded-xl shadow-lg p-6  bg-zinc-50 dark:bg-zinc-900">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $room->name }}
                        </h3>
                        <span
                            class="text-xs px-2 py-1 rounded-full
                    {{ $room->status === 'active'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                        : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                        <strong>Room ID:</strong> {{ $room->id }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        <strong>Lab In-Charge:</strong> {{ $room->labInCharge?->name ?? '—' }}
                    </p>

                    <div class="flex space-x-2">


                        @can('update.laboratories')
                            <button wire:click="openEditModal({{ $room->id }})"
                                class="text-blue-500 text-sm font-medium hover:underline cursor-pointer">Edit</button>
                        @endcan

                        @can('delete.laboratories')
                            <button wire:click="deleteRoom({{ $room->id }})"
                                class="text-red-500 text-sm font-medium hover:underline cursor-pointer">Delete</button>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>


    </div>

    {{-- Room Modal --}}
    @if ($modal === 'create' || $modal === 'edit')
        <livewire:rooms.room-form :room-id="$id" />
    @endif
</div>
