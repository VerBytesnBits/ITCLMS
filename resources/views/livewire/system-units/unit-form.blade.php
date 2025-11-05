<div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 px-2 sm:px-4"
    x-data="{ showParts: false }" x-cloak>

    <!-- Modal -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-screen-lg
                max-h-[95vh] flex flex-col animate-[fade-in-scale_0.2s_ease-out]"
        :class="showParts ? 'max-w-screen-xl' : 'max-w-screen-md'">
        <!-- Header -->
        <div
            class="flex items-center justify-between px-6 py-4 rounded-t-2xl
           bg-gradient-to-r from-blue-700 via-blue-500 to-blue-400 dark:from-blue-300 dark:to-blue-900 text-white border-b border-blue-500 ">
            <h2 class="text-lg sm:text-xl font-semibold">
                {{ $mode === 'create' ? 'Add Unit' : 'Edit Unit' }}
            </h2>

            <button wire:click="$dispatch('closeModal')" class="p-2 rounded-full hover:bg-red-500 transition">
                <flux:icon.x class="w-5 h-5" />
            </button>
        </div>


        <!-- Body -->
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div class="grid gap-6" :class="showParts ? 'grid-cols-1 md:grid-cols-[38%_62%]' : 'grid-cols-1'">

                <!-- Left Column -->
                <div>
                    <form id="unit-form" wire:submit.prevent="save" class="space-y-5">

                        <!-- Room -->
                        <flux:select wire:model.live="room_id" label="Assign Room">
                            <flux:select.option value="">Select Room</flux:select.option>
                            @foreach ($rooms as $room)
                                <flux:select.option value="{{ $room->id }}">{{ $room->name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <!-- Category -->
                        <flux:select wire:model.live="category" label="Category">
                            <flux:select.option value="">Select Unit</flux:select.option>
                            <flux:select.option value="PC">PC</flux:select.option>
                        </flux:select>

                        <!-- Unit Name -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Unit Name</label>
                            <flux:input type="text" wire:model="name"
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-700 
                                       bg-gray-50 dark:bg-zinc-800"
                                disabled />
                            <small class="text-gray-500 dark:text-gray-400">
                                Auto-generated from category. If adding more, names will increment (e.g., PC1, PC2...).
                            </small>
                        </div>

                        <!-- Add More Checkbox -->
                        @if ($mode === 'edit')
                            <flux:checkbox wire:model.live="multiple" label="Add more" disabled />
                        @else
                            <flux:checkbox wire:model.live="multiple" label="Add more" />
                        @endif

                        <!-- Quantity / Serial -->
                        @if ($multiple)
                            <div>
                                <label class="block text-sm font-medium">Quantity</label>
                                <flux:input type="number" wire:model="quantity" min="1" />
                            </div>

                            <div
                                class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-zinc-800 
                                       border border-gray-200 dark:border-zinc-700 rounded-lg px-3 py-2">
                                Serial numbers will be auto-generated for each item.
                            </div>
                        @else
                            <flux:input label="Serial Number" type="text" wire:model="serial_number"
                                placeholder="Enter serial number" />
                        @endif

                        <!-- Status -->
                        <flux:select wire:model.defer="status" label="Status">
                            <flux:select.option value="Operational">Operational</flux:select.option>
                            <flux:select.option value="Non-operational">Non-operational</flux:select.option>
                            <flux:select.option value="Needs Repair">Needs Repair</flux:select.option>
                        </flux:select>

                        <!-- Toggle Attach Parts -->
                        <div class="mt-4">
                            <template x-if="!showParts">
                                <button type="button" class="text-blue-600 hover:underline" @click="showParts = true">
                                    + Attach parts
                                </button>
                            </template>
                            <template x-if="showParts">
                                <button type="button" class="text-red-600 hover:underline" @click="showParts = false">
                                    – Don’t add parts
                                </button>
                            </template>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Parts -->
                <div class="border-t md:border-t-0 md:border-l pt-4 md:pt-0 pl-0 md:pl-6" x-show="showParts"
                    x-transition>
                    <livewire:system-units.unit-assign-parts :unitId="$unitId" />
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div
            class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-zinc-700 
                    bg-white dark:bg-zinc-900 sticky bottom-0">
            <button type="button"
                class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 dark:text-white rounded-lg 
                       hover:bg-gray-300 dark:hover:bg-zinc-600"
                wire:click="$dispatch('closeModal')">
                Cancel
            </button>
            <button type="submit" form="unit-form"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                {{ $mode === 'create' ? 'Add Unit' : 'Update Unit' }}
            </button>
        </div>
        
    </div>
    
</div>

