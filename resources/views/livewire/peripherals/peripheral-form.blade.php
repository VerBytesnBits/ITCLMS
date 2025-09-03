<div>
    @if ($modalMode)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 px-4">
            <div
                class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">

                {{-- <h2 class="text-xl font-semibold mb-4">
                    {{ $peripheral ? 'Edit Peripheral' : 'Create Peripheral' }}
                </h2> --}}

           
                    <form wire:submit.prevent="save" class="space-y-6">
                        <flux:fieldset>
                            <flux:legend class="text-xl font-semibold mb-4">
                                {{ $peripheral ? 'Edit Peripheral' : 'Add Peripheral' }}
                            </flux:legend>

                            {{-- Serial Number --}}
                            <flux:input label="Serial Number" placeholder="SAMPLE-1" wire:model="serial_number" />

                            {{-- Brand & Model --}}
                            <div class="grid grid-cols-2 gap-4">
                                <flux:input label="Brand" placeholder="HP / Logitech" wire:model="brand" />
                                <flux:input label="Model" placeholder="Model name" wire:model="model" />
                            </div>

                            {{-- Color --}}
                            <flux:input label="Color" placeholder="Black" wire:model="color" />

                            {{-- Type --}}
                            <flux:select label="Type" wire:model="type">
                                <option value="">Select Type</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Printer">Printer</option>
                                <option value="Speaker">Speaker</option>
                                <option value="Projector">Projector</option>
                                
                            </flux:select>

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

                        {{-- Actions --}}
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
