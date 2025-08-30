<!-- resources/views/livewire/system-units/unit-form.blade.php -->
<div x-data="{ open: @entangle('show') }">
    <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Add Unit</h2>

            <form wire:submit.prevent="save" class="space-y-3">
                <div>
                    <label>Name</label>
                    <input type="text" wire:model="name" class="w-full border rounded p-2">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Serial Number</label>
                    <input type="text" wire:model="serial_number" class="w-full border rounded p-2">
                    @error('serial_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Room</label>
                    <select wire:model="room_id" class="w-full border rounded p-2">
                        <option value="">-- Select Room --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Status</label>
                    <select wire:model="status" class="w-full border rounded p-2">
                        <option>Available</option>
                        <option>In Use</option>
                        <option>Under Maintenance</option>
                        <option>Defective</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open=false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
