<div>
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
                   animate-[fade-in-scale_0.2s_ease-out]">

            <!-- Title -->
            <h2 class="text-xl font-semibold mb-6">
                {{ $mode === 'create' ? 'Add Unit' : 'Edit Unit' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <!-- Room -->
                {{-- <div>
                    <label class="block text-sm font-medium mb-1">Assign Room</label>
                    <select wire:model.defer="room_id"
                        class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900">
                        <option value="">-- Select Room --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}
                {{-- <flux:radio.group wire:model.defer="room_id" label="Assign Room" variant="segmented">
                    @foreach ($rooms as $room)
                        <flux:radio label="{{ $room->name }}" value="{{ $room->id }}" />
                    @endforeach
                </flux:radio.group> --}}
                <flux:select wire:model.defer="room_id" label="Assign Room">
                    <flux:select.option value="">Select Room</flux:select.option>
                    @foreach ($rooms as $room)
                        <flux:select.option value="{{ $room->id }}">{{ $room->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="category" label="Category">
                    <flux:select.option value="">Select Unit</flux:select.option>
                    <flux:select.option value="PC">PC</flux:select.option>
                    <flux:select.option value="SERVER">SERVER</flux:select.option>
                    <flux:select.option value="LAPTOP">LAPTOP</flux:select.option>
                </flux:select>
                <!-- Unit Name (auto-generated) -->
                <div>
                    <label class="block text-sm font-medium mb-1">Unit Name</label>
                    <input type="text" wire:model="name"
                        class="w-full rounded-lg border-gray-300 bg-gray-100 dark:border-zinc-700 dark:bg-zinc-900"
                        readonly>
                    <small class="text-gray-500 dark:text-gray-400">
                        Auto-generated from category. If multiple, names will increment (e.g., PC1, PC2, ...).
                    </small>
                </div>

                <!-- Serial Number / Auto Notice -->
                <div>
                    @if ($multiple)
                        <div
                            class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-zinc-900 border 
                                   border-gray-200 dark:border-zinc-700 rounded-lg px-3 py-2">
                            Serial numbers will be auto-generated for each unit.
                        </div>
                    @else
                        <flux:input label="Serial Number" type="text" wire:model="serial_number"
                            placeholder="Enter serial number" />
                    @endif
                </div>

                <!-- Multiple Units Checkbox -->
                <flux:checkbox wire:model.live="multiple" label="Add multiple units" />

                <!-- Quantity (only if multiple) -->
                @if ($multiple)
                    <flux:input label="Quantity" type="number" wire:model="quantity" min="1" class="mt-2" />
                @endif

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select wire:model.defer="status"
                        class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900">
                        <option value="Operational">Operational</option>
                        <option value="Non-operational">Non-operational</option>
                        <option value="Needs Repair">Needs Repair</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button"
                        class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600"
                        wire:click="$dispatch('closeModal')">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        {{ $mode === 'create' ? 'Add Unit' : 'Update Unit' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
