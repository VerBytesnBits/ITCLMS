<div>
    @if ($show)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
            <div
                class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-96 h-96 flex flex-col p-6
               animate-[fade-in-scale_0.2s_ease-out] overflow-auto">

                <h2 class="text-xl font-bold mb-4"> {{ $partId ? 'Edit PSU' : 'Add PSU' }} </h2>

                <div class="space-y-2">
                    <!-- System Unit -->
                    <div>
                        <label>System Unit</label>
                        <select wire:model="system_unit_id" class="w-full border p-2 rounded">
                            <option value="">Select Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('system_unit_id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Serial Number -->
                    <div>
                        <label>Serial Number</label>
                        <input type="text" wire:model="serial_number" class="w-full border p-2 rounded">
                        @error('serial_number')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Brand / Model / Type / Capacity / Speed -->
                    <div>
                        <label>Brand</label>
                        <input type="text" wire:model="brand" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label>Model</label>
                        <input type="text" wire:model="model" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label>Type</label>
                        <input type="text" wire:model="type" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label>Capacity</label>
                        <input type="text" wire:model="capacity" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label>Speed</label>
                        <input type="text" wire:model="speed" class="w-full border p-2 rounded">
                    </div>

                    <!-- Condition -->
                    <div>
                        <label>Condition</label>
                        <select wire:model="condition" class="w-full border p-2 rounded">
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label>Status</label>
                        <select wire:model="status" class="w-full border p-2 rounded">
                            <option value="Available">Available</option>
                            <option value="In Use">In Use</option>
                            <option value="Under Maintenance">Under Maintenance</option>
                            <option value="Defective">Defective</option>
                        </select>
                    </div>

                    <!-- Warranty -->
                    <div>
                        <label>Warranty</label>
                        <input type="text" wire:model="warranty" class="w-full border p-2 rounded">
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-2 mt-4">
                        <button wire:click="$set('show', false)" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                        <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
