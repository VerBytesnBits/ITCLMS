<div class="p-6 space-y-6">
    {{-- Create Button --}}
    @can('create.laboratories')
        <div class="flex justify-end">
            {{-- <button wire:click="openCreateModal"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition duration-200">
                Create Room
            </button> --}}
            <flux:button variant="primary" color="lime" wire:click="openCreateModal">Add Room</flux:button>
        </div>
    @endcan

    {{-- Rooms Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($rooms as $room)
            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-6 flex flex-col justify-between">
                
                {{-- Header --}}
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $room->name }}</h3>
                    <span
                        class="text-xs font-semibold px-3 py-1 rounded-full 
                        {{ $room->status === 'Available'
                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ ucfirst($room->status) }}
                    </span>
                </div>

                {{-- Lab In-Charge --}}
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                    <strong>Lab In-Charge:</strong> {{ $room->labInCharge?->name ?? '—' }}
                </p>

                {{-- Actions --}}
                <div class="flex space-x-3 mt-auto">
                    @can('update.laboratories')
                        {{-- <button wire:click="openEditModal({{ $room->id }})"
                            class="flex-1 text-blue-600 dark:text-blue-400 font-medium text-sm hover:underline focus:outline-none">
                            Edit
                        </button> --}}
                        <flux:button variant="primary" color="blue" size="sm"  wire:click="openEditModal({{ $room->id }})">Edit</flux:button>
                    @endcan

                    @can('delete.laboratories')
                        {{-- <button wire:click="deleteRoom({{ $room->id }})"
                            class="flex-1 text-red-600 dark:text-red-400 font-medium text-sm hover:underline focus:outline-none">
                            Delete
                        </button> --}}
                        <flux:button variant="danger" size="sm" wire:click="deleteRoom({{ $room->id }})">Delete</flux:button>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>

    {{-- Room Modal --}}
    @if ($modal === 'create' || $modal === 'edit')
        <livewire:rooms.room-form :room-id="$id" />
    @endif
</div>
