<div>
    @if ($modalMode)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
            <div
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $componentId ? 'Edit Component Part' : 'Create Component Part' }}
                </h2>

                <div class="p-6">
                    <form wire:submit.prevent="save" class="space-y-4">
                        
                        <!-- System Unit -->
                        <div>
                            <label class="block text-sm font-medium">System Unit</label>
                            <select wire:model="system_unit_id" class="w-full rounded border-gray-300">
                                <option value="">-- None --</option>
                                @foreach ($systemUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name ?? 'Unit #' . $unit->id }}</option>
                                @endforeach
                            </select>
                            @error('system_unit_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Serial Number -->
                        <div>
                            <label class="block text-sm font-medium">Serial Number</label>
                            <input type="text" wire:model="serial_number" class="w-full rounded border-gray-300">
                            @error('serial_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Brand + Model -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Brand</label>
                                <input type="text" wire:model="brand" class="w-full rounded border-gray-300">
                                @error('brand') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Model</label>
                                <input type="text" wire:model="model" class="w-full rounded border-gray-300">
                                @error('model') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Capacity + Speed -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Capacity</label>
                                <input type="text" wire:model="capacity" placeholder="e.g. 500GB / 16GB"
                                    class="w-full rounded border-gray-300">
                                @error('capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Speed</label>
                                <input type="text" wire:model="speed" placeholder="e.g. 3200MHz / 7200RPM"
                                    class="w-full rounded border-gray-300">
                                @error('speed') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium">Type</label>
                            <input type="text" wire:model="type" placeholder="e.g. DDR4, SSD, HDD, ATX"
                                class="w-full rounded border-gray-300">
                            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Part -->
                        <div>
                            <label class="block text-sm font-medium">Part</label>
                            <select wire:model="part" class="w-full rounded border-gray-300">
                                <option value="">-- Select Part --</option>
                                <option value="Processor">Processor</option>
                                <option value="Motherboard">Motherboard</option>
                                <option value="Memory">Memory</option>
                                <option value="Graphics Card">Graphics Card</option>
                                <option value="Drive">Drive</option>
                                <option value="Power Supply">Power Supply</option>
                                <option value="Computer Case">Computer Case</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('part') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Condition + Status -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Condition</label>
                                <select wire:model="condition" class="w-full rounded border-gray-300">
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                </select>
                                @error('condition') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Status</label>
                                <select wire:model="status" class="w-full rounded border-gray-300">
                                    <option value="Available">Available</option>
                                    <option value="In Use">In Use</option>
                                    <option value="Defective">Defective</option>
                                    <option value="Under Maintenance">Under Maintenance</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Warranty -->
                        <div>
                            <label class="block text-sm font-medium">Warranty</label>
                            <input type="date" wire:model="warranty" class="w-full rounded border-gray-300">
                            @error('warranty') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" wire:click="$dispatch('closeModal')"
                                class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                {{ $modalMode === 'create' ? 'Create' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    @endif
</div>
