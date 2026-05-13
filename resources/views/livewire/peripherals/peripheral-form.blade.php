<div x-data="{ open: @entangle('modalMode') }" wire:ignore.self x-cloak x-on:keydown.escape.window="$dispatch('closeModal')">

    <!-- Modal Backdrop -->
    <div x-show="open" x-transition.opacity.duration.300ms class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4" style="display: none;">

        <!-- Card Container -->
        <div x-show="open" x-transition.origin.top.duration.300ms.scale.95 class="bg-white dark:bg-zinc-800 shadow-2xl rounded-2xl w-full max-w-xl overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-4 bg-linear-to-r from-blue-600 via-blue-700 to-blue-800
                       text-white flex justify-between items-center">
                <flux:legend class="text-xl font-semibold mb-0 text-white!">
                    {{ $peripheralId ? 'Update Peripheral' : 'Add Peripheral' }}
                </flux:legend>

                <button wire:click="$dispatch('closeModal')" class="p-2 rounded-full hover:bg-red-500 transition">
                    <flux:icon.x class="w-5 h-5" />
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6 text-lg">
                <form wire:submit.prevent="save" class="space-y-6">
                    <flux:fieldset>
                        @if ($modalMode === 'edit')
                        <flux:label>
                            <h1 class="text-xl font-semibold">Category <span class="text-red-500 text-2xl">*</span></h1> 
                        </flux:label>
                        <flux:select wire:model.live="type" disabled>

                            <option value="">Select Component Category </option>
                            <option value="Monitor" {{ $type === 'Monitor' ? 'selected' : '' }}>Monitor</option>
                            <option value="Keyboard" {{ $type === 'Keyboard' ? 'selected' : '' }}>Keyboard
                            </option>
                            <option value="Mouse" {{ $type === 'Mouse' ? 'selected' : '' }}>Mouse</option>
                            <option value="Speaker" {{ $type === 'Speaker' ? 'selected' : '' }}>Speaker</option>
                            <option value="AVR" {{ $type === 'AVR' ? 'selected' : '' }}>AVR</option>
                            <option value="UPS" {{ $type === 'UPS' ? 'selected' : '' }}>UPS</option>
                        </flux:select>
                        @else
                        <flux:label>
                            <h1 class="text-xl font-semibold">Category <span class="text-red-500 text-2xl">*</span> {{ $type ? ': ' . $type : '' }}</h1>
                        </flux:label>
                        <flux:select wire:model.live="type" size="2">

                            <option value="Monitor" class="text-2xl">Monitor</option>
                            <option value="Keyboard" class="text-2xl">Keyboard</option>
                            <option value="Mouse" class="text-2xl">Mouse</option>
                            <option value="Speaker" class="text-2xl">Speaker</option>
                            <option value="AVR" class="text-2xl">AVR</option>
                            <option value="UPS" class="text-2xl">UPS</option>
                        </flux:select>
                        @endif


                        <div class="space-y-2 mb-2">
                            <flux:label>
                                <h1 class="text-xl font-semibold">Room <span class="text-red-500 text-2xl">*</span></h1> 
                            </flux:label>

                            <div class="grid grid-cols-2 gap-2 ">
                                {{-- Unassigned (default) --}}
                                <label class="flex items-center gap-2">
                                    <input type="radio" wire:model="room_id" value="" class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">Unassigned</span>
                                </label>

                                @foreach ($rooms as $room)
                                <label class="flex items-center gap-2">
                                    <input type="radio" wire:model="room_id" value="{{ $room->id }}" class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">{{ $room->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Serial / Quantity --}}
                        @if ($multiple)
                        <div class="text-sm text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900
                                       border border-blue-200 dark:border-blue-700
                                       rounded-lg px-3 py-2">
                            Serial numbers will be auto-generated for
                            <strong>{{ $quantity }}</strong> items.
                        </div>

                        <flux:input type="number" label="Quantity" wire:model="quantity" min="1" required />
                        @else
                        <flux:label>
                            <h1 class="text-xl font-semibold">Serial Number <span class="text-red-500 text-2xl">*</span></h1> 
                        </flux:label>
                        <flux:input wire:model="serial_number" />
                        @endif

                        {{-- Brand & Model --}}
                        <div class="grid grid-cols-2 gap-4 mb-2 mt-2">

                            <div>
                                <flux:label class="text-xl font-semibold">
                                    <h1>Brand <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:input class="text-lg" wire:model="brand" required />
                            </div>

                            <div>
                                <flux:label class="text-xl font-semibold">
                                     <h1>Model <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:input class="text-lg" wire:model="model" required />
                            </div>

                        </div>

                        {{-- Peripheral-Specific Fields --}}
                        @if ($type)
                        <div class="pt-4 border-t border-gray-200 dark:border-zinc-700 space-y-4">
                
                            @switch($type)
                            @case('Monitor')
                            <flux:input label="Screen Size (inches)" wire:model="screen_size" type="number" step="0.1" />
                            @break

                            @case('Keyboard')
                            <flux:select label="Switch Type" wire:model="switch_type">
                                <option value="">Select Type</option>
                                <option value="Mechanical">Mechanical</option>
                                <option value="Membrane">Membrane</option>
                            </flux:select>
                            @break

                            @case('Mouse')
                            <flux:input label="DPI" wire:model="dpi" type="number" placeholder="e.g. 1600" />
                            @break

                            @case('Speaker')
                            <flux:input label="Wattage (W)" wire:model="wattage" placeholder="e.g. 20W" />
                            @break

                            @case('Webcam')
                            <flux:input label="Resolution" wire:model="resolution" placeholder="1080p / 4K" />
                            @break

                            @case('AVR')
                            @case('UPS')
                            <flux:input label="Capacity (VA)" wire:model="capacity_va" placeholder="e.g. 500VA" />
                            @break
                            @endswitch
                        </div>
                        @endif

                        {{-- Status --}}
                        <flux:select label="Status" wire:model="status" required>
                            <option value="Available">Available</option>
                            <option value="In Use">In Use</option>
                            <option value="Defective">Defective</option>
                            <option value="Under Maintenance">Under Maintenance</option>
                        </flux:select>

                        {{-- Purchase & Warranty --}}
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input type="date" label="Purchase Date" wire:model="purchase_date" />

                            <flux:input type="number" label="Warranty (months)" wire:model="warranty_period_months" min="0" />
                        </div>

                        {{-- Warranty Preview --}}
                        @if ($purchase_date && $warranty_period_months)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Warranty expires on
                            <strong class="text-blue-600 dark:text-blue-400">
                                {{ \Carbon\Carbon::parse($purchase_date)
                                        ->addMonths((int) $warranty_period_months)
                                        ->format('F d, Y') }}
                            </strong>
                        </p>
                        @endif

                    </flux:fieldset>

                    <!-- Footer -->
                    <div class="bg-gray-50 dark:bg-zinc-800 flex justify-end space-x-2">
                        <flux:button variant="filled" wire:click="$dispatch('closeModal')">
                            Cancel
                        </flux:button>

                        <flux:button variant="primary" type="submit">
                            {{ $peripheralId ? 'Update' : 'Add' }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
