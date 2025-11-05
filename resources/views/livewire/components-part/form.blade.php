<div x-data="{ open: @entangle('modalMode') }">
    <!-- Modal Backdrop -->
    <div x-show="open" x-transition.opacity.duration.300ms
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4"
        style="display: none;">
        <!-- Card Container -->
        <div x-show="open" x-transition.origin.top.duration.300ms.scale.95
            class="bg-white dark:bg-zinc-800 shadow-2xl rounded-2xl w-full max-w-xl overflow-hidden">
            <!-- Header -->
            <div
                class="px-6 py-4 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 text-white flex justify-between items-center">
                <flux:legend class="text-xl font-semibold mb-0 !text-white">
                    {{ $componentId ? 'Edit Component' : 'Add Component' }}
                </flux:legend>
                <button type="button" class="p-2 rounded-lg hover:bg-red-500 transition"
                    wire:click="$dispatch('closeModal')" title="Close">✕</button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6">
                <form wire:submit.prevent="save" class="space-y-6">
                    <flux:fieldset>

                        {{-- Part --}}
                        @if ($modalMode === 'edit')
                            <flux:select label="Category" wire:model.live="part" disabled>
                                <option value="">Select Component Category</option>
                                <option value="CPU" {{ $part === 'CPU' ? 'selected' : '' }}>CPU</option>
                                <option value="Motherboard" {{ $part === 'Motherboard' ? 'selected' : '' }}>Motherboard
                                </option>
                                <option value="RAM" {{ $part === 'RAM' ? 'selected' : '' }}>RAM</option>
                                <option value="GPU" {{ $part === 'GPU' ? 'selected' : '' }}>GPU</option>
                                <option value="Storage" {{ $part === 'Storage' ? 'selected' : '' }}>Storage</option>
                                <option value="PSU" {{ $part === 'PSU' ? 'selected' : '' }}>PSU</option>
                                <option value="Casing" {{ $part === 'Casing' ? 'selected' : '' }}>Computer Case</option>
                            </flux:select>
                        @else
                            <flux:select label="Category" wire:model.live="part">
                                <option value="">Select Category Type</option>
                                <option value="CPU">CPU</option>
                                <option value="Motherboard">Motherboard</option>
                                <option value="RAM">RAM</option>
                                <option value="GPU">GPU</option>
                                <option value="Storage">Storage</option>
                                <option value="PSU">PSU</option>
                                <option value="Casing">Computer Case</option>
                            </flux:select>
                        @endif

                        {{-- Serial Number --}}
                        @if ($multiple)
                            <div
                                class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-zinc-800 border 
                                border-gray-200 dark:border-zinc-700 rounded-lg px-3 py-2 mt-2 mb-2">
                                Serial numbers will be auto-generated for each item.
                            </div>
                        @else
                            <flux:input label="Serial Number" type="text" wire:model="serial_number" />
                        @endif

                        {{-- Multiple Checkbox --}}
                        @if ($modalMode === 'edit')
                            <flux:checkbox wire:model.live="multiple" label="Add more" disabled />
                        @else
                            <flux:checkbox wire:model.live="multiple" label="Add more" />
                        @endif

                        {{-- Quantity --}}
                        @if ($multiple)
                            <label class="block text-sm font-medium">Quantity</label>
                            <flux:input type="number" wire:model="quantity" min="1" />
                        @endif

                        {{-- Brand & Model --}}
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input list="brands" label="Brand" wire:model="brand"
                                placeholder="Intel / AMD / Custom" />
                            <datalist id="brands">
                                <option value="Intel">
                                <option value="AMD">
                                <option value="Asus">
                                <option value="Samsung">
                                <option value="Western Digital">
                                <option value="Seagate">
                                <option value="Kingston">
                            </datalist>

                            <flux:input list="models" label="Model" wire:model="model"
                                placeholder="Model (e.g. Ryzen, Core)" />
                            <datalist id="models">
                                @if (strtolower($brand) === 'intel')
                                    <option value="Core i5">
                                    <option value="Core i7">
                                    <option value="Core i9">
                                    @elseif (strtolower($brand) === 'amd')
                                    <option value="Ryzen 5 5600G">
                                    <option value="Ryzen 7 5700X">
                                    @elseif (strtolower($brand) === 'asus')
                                    <option value="PRIME Z690">
                                    <option value="ROG Strix">
                                @endif
                            </datalist>
                        </div>

                        {{-- Conditional Fields --}}
                        @if ($part === 'CPU')
                            <flux:select label="Speed" wire:model="speed">
                                <option value="">Select Speed</option>
                                <option value="2.5GHz">2.5GHz</option>
                                <option value="3.2GHz">3.2GHz</option>
                                <option value="3.6GHz">3.6GHz</option>
                                <option value="3.9GHz">3.9GHz</option>
                            </flux:select>
                        @elseif ($part === 'RAM')
                            <div class="grid grid-cols-2 gap-4">
                                <flux:select label="Capacity" wire:model="capacity">
                                    <option value="">Select Capacity</option>
                                    <option value="4GB">4GB</option>
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
                        @elseif ($part === 'Storage')
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
                        @elseif ($part === 'GPU')
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
                                <option value="Junk">Junk</option>
                                <option value="Defective">Defective</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </flux:select>
                        </div>

                        {{-- Warranty --}}
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input type="date" label="Purchase Date" wire:model="purchase_date"
                                @if ($modalMode === 'edit') disabled @endif />
                            <flux:input type="number" label="Warranty Period (months)"
                                wire:model="warranty_period_months"
                                @if ($modalMode === 'edit') disabled @endif />
                        </div>
                           {{-- Purchase Date & Warranty --}}

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input type="date" label="Purchase Date" wire:model="purchase_date" />
                            <flux:input type="number" label="Warranty Period (months)"
                                wire:model="warranty_period_months" />
                        </div>
   
                        {{-- Live Preview --}}
                        @if ($purchase_date && $warranty_period_months)
                            <p class="text-sm text-gray-600 mt-2">
                                Warranty expires on:
                                <strong>{{ \Carbon\Carbon::parse($purchase_date)->addMonths((int) $warranty_period_months)->format('M d, Y') }}</strong>
                            </p>
                        @endif

                    </flux:fieldset>
                    <!-- Footer -->
                    <div class="bg-gray-50 dark:bg-zinc-900 flex justify-end space-x-2">
                        <flux:button variant="filled" wire:click="$dispatch('closeModal')">Cancel</flux:button>
                        <flux:button variant="primary" type="submit">
                            {{ $modalMode === 'create' ? 'Add' : 'Update' }}
                        </flux:button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
