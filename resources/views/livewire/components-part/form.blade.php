<div x-data="{ open: @entangle('modalMode') }" x-on:keydown.escape.window="$dispatch('closeModal')">

    <!-- Modal Backdrop -->
    <div x-show="open" wire:ignore.self x-transition.opacity.duration.300ms class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4" style="display: none;">
        <!-- Card Container -->
        <div x-show="open" x-transition.origin.top.duration.300ms.scale.95 class="bg-white dark:bg-zinc-800 shadow-2xl rounded-2xl w-full max-w-xl overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-linear-to-r from-blue-600 via-blue-700 to-blue-800 text-white flex justify-between items-center">
                <flux:legend class="text-xl font-semibold mb-0 text-white!">
                    {{ $componentId ? 'Update Component' : 'Add Component' }}
                </flux:legend>
                <button wire:click="$dispatch('closeModal')" class="p-2 rounded-full hover:bg-red-500 transition">
                    <flux:icon.x class="w-5 h-5" />
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6">
                <form wire:submit.prevent="save" class="space-y-6">
                    <flux:fieldset>

                        {{-- Part --}}
                        @if ($modalMode === 'edit')
                        <flux:label>
                            <h1 class="text-xl font-semibold">Category <span class="text-red-500 text-2xl">*</span></h1>
                        </flux:label>
                        <flux:select wire:model.live="part" disabled>
                            <option value="">Select Component Category</option>
                            <option value="CPU" {{ $part === 'CPU' ? 'selected' : '' }}>CPU</option>
                            <option value="Motherboard" {{ $part === 'Motherboard' ? 'selected' : '' }}>Motherboard
                            </option>
                            <option value="RAM" {{ $part === 'RAM' ? 'selected' : '' }}>RAM</option>
                            <option value="GPU" {{ $part === 'GPU' ? 'selected' : '' }}>GPU</option>
                            <option value="Storage" {{ $part === 'Storage' ? 'selected' : '' }}>Storage</option>
                            <option value="PSU" {{ $part === 'PSU' ? 'selected' : '' }}>PSU</option>
                            <option value="Casing" {{ $part === 'Casing' ? 'selected' : '' }}>Casing</option>
                        </flux:select>
                        @else
                        <flux:label>
                            <h1 class="text-xl font-semibold">Category <span class="text-red-500 text-2xl">*</span> {{ $part ? ': ' . $part : '' }}</h1>
                        </flux:label>
                        <flux:select wire:model.live="part" size="6">

                            <option value="CPU" class="text-2xl">CPU</option>
                            <option value="Motherboard" class="text-2xl">Motherboard</option>
                            <option value="RAM" class="text-2xl">RAM</option>
                            <option value="GPU" class="text-2xl">GPU</option>
                            <option value="Storage" class="text-2xl">Storage</option>
                            <option value="PSU" class="text-2xl">PSU</option>
                            <option value="Casing" class="text-2xl">Casing</option>
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

                        <flux:label>
                            <h1 class="text-xl font-semibold">Serial Number <span class="text-red-500 text-2xl">*</span></h1>
                        </flux:label>
                        <flux:input type="text" wire:model="serial_number" />

                        {{-- Brand & Model --}}
                        <div class="grid grid-cols-2 gap-4 mb-2 mt-2">

                            {{-- Brand --}}
                            <div>
                                <flux:label class="text-lg ">
                                   <h1> Brand <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:input class="text-lg" list="brands" wire:model="brand" />

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

                            {{-- Model --}}
                            <div>
                                <flux:label class="text-lg ">
                                   <h1> Model <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:input class="text-lg" list="models" wire:model="model" />

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

                        </div>

                        {{-- Conditional Fields --}}
                        @if ($part === 'CPU')
                        <flux:label>
                            <h1>Speed/GHz <span class="text-red-500 text-2xl">*</span></h1>
                        </flux:label>
                        <flux:input wire:model="speed" mask="9.9GHz" />
                        @elseif ($part === 'RAM')
                        <div class="grid grid-cols-2 gap-4">

                            {{-- Capacity --}}
                            <div>
                                <flux:label class="text-lg ">
                                    <h1>Capacity <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:input class="text-lg" wire:model="capacity" mask="99GB" />
                            </div>

                            {{-- Type --}}
                            <div>
                                <flux:label class="text-lg">
                                    <h1>Type <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:select class="text-lg" wire:model="type">
                                    <option value="">Select Type</option>
                                    <option value="DDR3">DDR3</option>
                                    <option value="DDR4">DDR4</option>
                                    <option value="DDR5">DDR5</option>
                                </flux:select>
                            </div>

                        </div>
                        @elseif ($part === 'Storage')
                        <div class="grid grid-cols-2 gap-4">

                            {{-- Capacity --}}
                            <div>
                                <flux:label class="text-lg ">
                                    <h1>Capacity <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:select class="text-lg" wire:model="capacity">
                                    <option value="">Select Capacity</option>
                                    <option value="500GB">500GB</option>
                                    <option value="1TB">1TB</option>
                                    <option value="2TB">2TB</option>
                                </flux:select>
                            </div>

                            {{-- Type --}}
                            <div>
                                <flux:label class="text-lg">
                                    <h1>Type <span class="text-red-500 text-2xl">*</span></h1>
                                </flux:label>

                                <flux:select class="text-lg" wire:model="type">
                                    <option value="">Select Type</option>
                                    <option value="SSD">SSD</option>
                                    <option value="HDD">HDD</option>
                                </flux:select>
                            </div>

                        </div>
                        @elseif ($part === 'GPU')
                        <flux:label class="text-lg ">
                            <h1>Capacity <span class="text-red-500 text-2xl">*</span></h1>
                        </flux:label>
                        <flux:input wire:model="capacity" mask="9GB" />
                        @endif

                        {{-- Status --}}
                        <div class="grid grid-cols-1 gap-4 mb-2">
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
                            <flux:input type="date" label="Purchase Date" wire:model="purchase_date" @if ($modalMode==='edit' ) disabled @endif />
                            <flux:input type="number" label="Warranty Period (months)" wire:model="warranty_period_months" @if ($modalMode==='edit' ) disabled @endif />
                        </div>
                        {{-- Purchase Date & Warranty --}}

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input type="date" label="Purchase Date" wire:model="purchase_date" />
                            <flux:input type="number" label="Warranty Period (months)" wire:model="warranty_period_months" />
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
                    <div class="bg-gray-50 dark:bg-zinc-800 flex justify-end space-x-2">
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
