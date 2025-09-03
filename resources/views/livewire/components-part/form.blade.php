<div>
    @if ($modalMode)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
            <div
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
                animate-[fade-in-scale_0.2s_ease-out]">

                <form wire:submit.prevent="save" class="space-y-6">
                    <flux:fieldset>
                        <flux:legend class="text-xl font-semibold mb-4">
                            {{ $componentId ? 'Edit Component' : 'Add Component' }}
                        </flux:legend>

                        {{-- Choose Part First --}}
                        <flux:select label="Part" wire:model.live="part">
                            <option value="">Select Unit Part</option>
                            <option value="Processor">Processor</option>
                            <option value="Motherboard">Motherboard</option>
                            <option value="Memory">Memory</option>
                            <option value="Graphics Card">Graphics Card</option>
                            <option value="Drive">Drive</option>
                            <option value="Power Supply">Power Supply</option>
                            <option value="Computer Case">Computer Case</option>
                        </flux:select>

                        {{-- Always Required: Serial Number --}}
                        <flux:input label="Serial Number" wire:model="serial_number" placeholder="SAMPLE-1" />

                        {{-- Common Fields: Brand + Model --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Brand</label>
                                <input list="brands" wire:model.live="brand" placeholder="Intel / AMD / Custom"
                                    class="w-full border rounded-lg px-3 py-2">
                                <datalist id="brands">
                                    <option value="Intel">
                                    <option value="AMD">
                                    <option value="Asus">
                                    <option value="Samsung">
                                    <option value="Western Digital">
                                    <option value="Seagate">
                                    <option value="Kingston">
                                </datalist>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model</label>
                                <input list="models" wire:model.live="model" placeholder="Model (e.g. Ryzen, Core)"
                                    class="w-full border rounded-lg px-3 py-2">
                                <datalist id="models">
                                    @if (strtolower($brand) === 'intel')
                                        <option value="Core i5">
                                        <option value="Core i7">
                                        <option value="Core i9">
                                    @elseif (strtolower($brand) === 'amd')
                                        <option value="Ryzen 5">
                                        <option value="Ryzen 7">
                                        <option value="Ryzen 9">
                                    @elseif (strtolower($brand) === 'asus')
                                        <option value="PRIME Z690">
                                        <option value="ROG Strix">
                                    @endif
                                </datalist>
                            </div>
                        </div>

                        {{-- Conditional Fields --}}
                        @if ($part === 'Processor')
                            <flux:select label="Speed" wire:model="speed">
                                <option value="">Select Speed</option>
                                <option value="2.5GHz">2.5GHz</option>
                                <option value="3.6GHz">3.6GHz</option>
                                <option value="3.9GHz">3.9GHz</option>
                            </flux:select>
                        @endif

                        @if ($part === 'Memory')
                            <div class="grid grid-cols-2 gap-4">
                                <flux:select label="Capacity" wire:model="capacity">
                                    <option value="">Select Capacity</option>
                                    <option value="8GB">8GB</option>
                                    <option value="16GB">16GB</option>
                                    <option value="32GB">32GB</option>
                                </flux:select>

                                <flux:select label="Type" wire:model="type">
                                    <option value="">Select Type</option>
                                    <option value="DDR3">DDR3</option>
                                    <option value="DDR4">DDR4</option>
                                    <option value="DDR5">DDR5</option>
                                </flux:select>
                            </div>
                        @endif

                        @if ($part === 'Drive')
                            <div class="grid grid-cols-2 gap-4">
                                <flux:select label="Capacity" wire:model="capacity">
                                    <option value="">Select Capacity</option>
                                    <option value="500GB">500GB</option>
                                    <option value="1TB">1TB</option>
                                    <option value="2TB">2TB</option>
                                </flux:select>

                                <flux:select label="Type" wire:model="type">
                                    <option value="">Select Type</option>
                                    <option value="SSD">SSD</option>
                                    <option value="HDD">HDD</option>
                                </flux:select>
                            </div>
                        @endif

                        @if ($part === 'Graphics Card')
                            <flux:select label="Capacity" wire:model="capacity">
                                <option value="">Select VRAM</option>
                                <option value="4GB">4GB</option>
                                <option value="6GB">6GB</option>
                                <option value="8GB">8GB</option>
                            </flux:select>
                        @endif

                        {{-- Condition & Status --}}
                        <div class="grid grid-cols-2 gap-4">
                            <flux:select label="Condition" wire:model="condition">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </flux:select>

                            <flux:select label="Status" wire:model="status">
                                <option value="Available">Available</option>
                                <option value="In Use">In Use</option>
                                <option value="Defective">Defective</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </flux:select>
                        </div>

                        {{-- Warranty --}}
                        <flux:input type="date" label="Warranty" wire:model="warranty" />
                    </flux:fieldset>

                    {{-- Actions (unchanged) --}}
                    <div class="flex justify-end space-x-2 mt-4">
                        <flux:button variant="primary" color="red" wire:click="$dispatch('closeModal')">
                            Cancel
                        </flux:button>
                        <flux:button variant="primary" type="submit">
                            {{ $modalMode === 'create' ? 'Add' : 'Update' }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
