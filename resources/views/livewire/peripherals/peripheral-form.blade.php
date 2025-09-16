<div>
    @if ($modalMode)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
            <div
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl animate-[fade-in-scale_0.2s_ease-out]">

                <form wire:submit.prevent="save" class="space-y-6">
                    <flux:fieldset>
                        <flux:legend class="text-xl font-semibold mb-4">
                            {{ $peripheralId ? 'Edit Peripheral' : 'Add Peripheral' }}
                        </flux:legend>
                        {{-- Type --}}
                        <flux:select label="Category" wire:model.live="type">
                            <option value="">Select Category Type</option>
                            <option value="Monitor">Monitor</option>
                            <option value="Keyboard">Keyboard</option>
                            <option value="Mouse">Mouse</option>
                            <option value="Printer">Printer</option>
                            <option value="Speaker">Speaker</option>
                            <option value="Projector">Projector</option>
                            <option value="Webcam">Webcam</option>
                            <option value="AVR">AVR</option>
                            <option value="UPS">UPS</option>
                        </flux:select>
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

                        {{-- Checkbox --}}
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



                        {{-- Brand & Model (common) --}}
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <flux:input label="Brand" wire:model="brand" placeholder="HP / Logitech / Epson" />
                            <flux:input label="Model" wire:model="model" placeholder="Model name" />
                        </div>

                        {{-- Dynamic Fields based on Type --}}
                        <div class="mt-4">
                            @switch($type)
                                @case('Monitor')
                                    <flux:input label="Screen Size (inches)" wire:model="screen_size"
                                        placeholder="24 / 27 / 32" />
                                @break

                                @case('Keyboard')
                                    <flux:select label="Switch Type" wire:model="switch_type">
                                        <option value="">Select</option>
                                        <option value="Mechanical">Mechanical</option>
                                        <option value="Membrane">Membrane</option>
                                    </flux:select>
                                @break

                                @case('Mouse')
                                    <flux:input label="DPI" wire:model="dpi" placeholder="e.g. 1600" />
                                @break

                                @case('Printer')
                                    <flux:select label="Printer Type" wire:model="printer_type">
                                        <option value="">Select</option>
                                        <option value="Inkjet">Inkjet</option>
                                        <option value="Laser">Laser</option>
                                        <option value="Dot Matrix">Dot Matrix</option>
                                    </flux:select>
                                @break

                                @case('Speaker')
                                    <flux:input label="Wattage" wire:model="wattage" placeholder="e.g. 20W" />
                                @break

                                @case('Projector')
                                    <flux:input label="Lumens" wire:model="lumens" placeholder="e.g. 3500" />
                                @break

                                @case('Webcam')
                                    <flux:input label="Resolution" wire:model="resolution" placeholder="e.g. 1080p" />
                                @break

                                @case('AVR')
                                    <flux:input label="Capacity (VA)" wire:model="capacity_va" placeholder="e.g. 500VA" />
                                @break

                                @case('UPS')
                                    <flux:input label="Capacity (VA)" wire:model="capacity_va" placeholder="e.g. 1000VA" />
                                @break
                            @endswitch
                        </div>


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
                                <strong>
                                    {{ \Carbon\Carbon::parse($purchase_date)->addMonths((int) $warranty_period_months)->format('M d, Y') }}
                                </strong>
                            </p>
                        @endif

                    </flux:fieldset>

                    {{-- Actions --}}
                    <div class="flex justify-end space-x-2 mt-4">
                        <flux:button variant="filled" wire:click="$dispatch('closeModal')">Cancel</flux:button>
                        <flux:button variant="primary" type="submit">
                            {{ $modalMode === 'create' ? 'Add' : 'Update' }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
