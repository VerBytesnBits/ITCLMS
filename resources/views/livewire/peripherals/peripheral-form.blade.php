<div x-data="{ open: @entangle('modalMode') }" x-on:keydown.escape.window="$dispatch('closeModal')">
    <div x-show="open" x-transition.opacity.duration.300ms
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        style="display: none;">

        <div x-show="open" x-transition.origin.top.duration.300ms.scale.95
            class="bg-white dark:bg-zinc-900 shadow-2xl rounded-xl w-full max-w-2xl overflow-hidden transform transition-all">

            <form wire:submit.prevent="save">
                <div
                    class="px-6 py-4 bg-blue-600 text-white dark:bg-blue-700 flex justify-between items-center">
                    <h3 class="text-xl font-bold mb-0 !text-white">
                        {{ $peripheralId ? 'Update Peripheral' : 'Add New Peripheral' }}
                    </h3>
                    <button type="button" wire:click="$dispatch('closeModal')" class="p-1 rounded-full hover:bg-red-500 transition-colors">
                        <flux:icon.x class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <flux:fieldset>
                        <legend class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                            Core Details
                        </legend>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Type - Required --}}
                            <flux:select label="Category *" wire:model.live="type" required>
                                <option value="">Select Category Type</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Speaker">Speaker</option>
                                <option value="Webcam">Webcam</option>
                                <option value="AVR">AVR (Automatic Voltage Regulator)</option>
                                <option value="UPS">UPS (Uninterruptible Power Supply)</option>
                            </flux:select>

                           
                            <flux:select label="Room" wire:model="room_id">
                                <option value="">Unassigned</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </flux:select>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Brand - Required --}}
                            <flux:input label="Brand *" wire:model="brand" required />
                            {{-- Model - Required --}}
                            <flux:input label="Model *" wire:model="model" required />
                        </div>

                        <div>
                            @if ($multiple)
                                <div class="text-sm text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg px-4 py-3 mt-2 mb-4 font-medium">
                                    <flux:icon.info class="w-5 h-5 inline-block mr-2 align-middle"/>
                                    Serial numbers will be auto-generated for each of the **{{ $quantity }}** items.
                                </div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                                <flux:input type="number" wire:model="quantity" min="1" required />
                            @else
                                <flux:input label="Serial Number" type="text" wire:model="serial_number" placeholder="Enter unique identifier" />
                            @endif
                        </div>

                        @if ($type)
                            <div class="pt-4 border-t border-gray-200 dark:border-zinc-700">
                                <legend class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                                    {{ $type }} Specific Fields
                                </legend>
                                @switch($type)
                                    @case('Monitor')
                                        <flux:input label="Screen Size (inches)" wire:model="screen_size"
                                            placeholder="e.g., 24, 27" type="number" step="0.1" />
                                        @break

                                    @case('Keyboard')
                                        <flux:select label="Switch Type" wire:model="switch_type">
                                            <option value="">Select Switch Type</option>
                                            <option value="Mechanical">Mechanical</option>
                                            <option value="Membrane">Membrane</option>
                                        </flux:select>
                                        @break

                                    @case('Mouse')
                                        <flux:input label="DPI" wire:model="dpi" placeholder="e.g., 1600" type="number" />
                                        @break

                                    @case('Speaker')
                                        <flux:input label="Wattage (W)" wire:model="wattage" placeholder="e.g., 20W" />
                                        @break

                                    @case('Webcam')
                                        <flux:input label="Resolution" wire:model="resolution" placeholder="e.g., 1080p, 4K" />
                                        @break

                                    @case('AVR')
                                    @case('UPS')
                                        <flux:input label="Capacity (VA)" wire:model="capacity_va" placeholder="e.g., 500VA" />
                                        @break
                                @endswitch
                            </div>
                        @endif

                        <div class="pt-4 border-t border-gray-200 dark:border-zinc-700 grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Status - Required --}}
                            <flux:select label="Status *" wire:model="status" required>
                                <option value="Available">Available</option>
                                <option value="In Use">In Use</option>
                                <option value="Defective">Defective</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </flux:select>
                            
                            {{-- Purchase Date --}}
                            <flux:input type="date" label="Purchase Date" wire:model="purchase_date" />
                            
                            {{-- Warranty --}}
                            <flux:input type="number" label="Warranty (months)"
                                wire:model="warranty_period_months" min="0" />
                        </div>

                        {{-- Live Preview for Warranty --}}
                        @if ($purchase_date && $warranty_period_months)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 p-2 bg-gray-50 dark:bg-zinc-800 rounded-md">
                                **Warranty Expiration:**
                                <strong class="text-blue-600 dark:text-blue-400">
                                    {{ \Carbon\Carbon::parse($purchase_date)->addMonths((int) $warranty_period_months)->format('F d, Y') }}
                                </strong>
                            </p>
                        @endif
                        
                        <div class="text-xs text-red-500 mt-4 text-right">
                             * Indicates a required field.
                        </div>
                    </flux:fieldset>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800 flex justify-end space-x-3 border-t border-gray-200 dark:border-zinc-700">
                    <flux:button  wire:click.prevent="$dispatch('closeModal')">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">
                        {{ $peripheralId ? 'Save Changes' : 'Add Peripheral' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>