<div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 px-2 sm:px-4"
     x-data="{ showParts: true }" x-cloak x-on:keydown.escape.window="$dispatch('closeModal')">

    <!-- Modal -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-screen-lg max-h-[95vh] flex flex-col animate-[fade-in-scale_0.2s_ease-out]"
         :class="showParts ? 'max-w-screen-xl' : 'max-w-screen-md'">

        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-3 bg-blue-600 text-white rounded-t-2xl">
            <h2 class="text-lg sm:text-xl font-semibold">
                {{ $mode === 'create' ? 'Add Unit' : 'Update Unit' }}
            </h2>
            <button wire:click="$dispatch('closeModal')" class="p-2 rounded-full hover:bg-red-500 transition">
                <flux:icon.x class="w-5 h-5" />
            </button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div class="grid gap-6" :class="showParts ? 'grid-cols-1 md:grid-cols-[38%_62%]' : 'grid-cols-1'">

                <!-- Left Column: Unit Form -->
                <div>
                    <form id="unit-form" wire:submit.prevent="save" class="space-y-5"
                          wire:loading.class="opacity-50 pointer-events-none" wire:target="save">

                        <!-- Device Category -->
                        <label class="block text-lg font-semibold text-heading mb-3">Device Category</label>
                        <div class="grid grid-cols-2 gap-4 {{ $mode === 'edit' ? 'opacity-60 pointer-events-none select-none' : '' }}">
                            <!-- PC -->
                            <label for="category-pc"
                                   class="group p-5 border-2 rounded-xl cursor-pointer transition duration-200 ease-in-out 
                                   hover:shadow-md hover:border-brand-medium 
                                   {{ $category === 'PC' ? 'border-brand ring-2 ring-brand-subtle shadow-lg' : 'border-default-medium bg-neutral-primary-soft' }}">
                                <input id="category-pc" type="radio" name="category" value="PC"
                                       wire:model.defer="category" wire:change="generateUnitName" class="sr-only">
                                <div class="flex flex-col items-center text-center">
                                    <span class="text-3xl mb-2">{{ $category === 'PC' ? '✅' : '🖥️' }}</span>
                                    <span class="text-base font-bold text-heading">PC</span>
                                    <span class="text-xs text-secondary-medium mt-1">Desktop Computer</span>
                                </div>
                            </label>
                            <!-- LAPTOP -->
                            <label for="category-laptop"
                                   class="group p-5 border-2 rounded-xl cursor-pointer transition duration-200 ease-in-out 
                                   hover:shadow-md hover:border-brand-medium 
                                   {{ $category === 'LAPTOP' ? 'border-brand ring-2 ring-brand-subtle shadow-lg' : 'border-default-medium bg-neutral-primary-soft' }}">
                                <input id="category-laptop" type="radio" name="category" value="LAPTOP"
                                       wire:model.defer="category" wire:change="generateUnitName" class="sr-only">
                                <div class="flex flex-col items-center text-center">
                                    <span class="text-3xl mb-2">{{ $category === 'LAPTOP' ? '✅' : '💻' }}</span>
                                    <span class="text-base font-bold text-heading">LAPTOP</span>
                                    <span class="text-xs text-secondary-medium mt-1">Portable Device</span>
                                </div>
                            </label>
                        </div>

                        <!-- Assign Room -->
                        <label class="block text-lg font-semibold text-heading mb-3 mt-6">Assign Room</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($rooms as $room)
                                <label for="room-{{ $room->id }}"
                                       class="flex items-center p-4 rounded-lg cursor-pointer transition duration-150 ease-in-out 
                                       hover:bg-neutral-secondary-soft hover:shadow-sm
                                       {{ $room_id == $room->id ? 'border border-black shadow-lg' : 'border border-default-medium bg-neutral-primary-soft' }}">
                                    <input id="room-{{ $room->id }}" type="radio" name="room_id" value="{{ $room->id }}"
                                           wire:model.defer="room_id" wire:change="generateUnitName" class="hidden">
                                    <span class="text-sm font-medium text-heading flex-grow ">{{ $room->name }}</span>
                                    <div class="w-6 h-6 flex items-center justify-center rounded-full border shrink-0
                                        {{ $room_id == $room->id ? 'bg-yellow-500 border-yellow-500' : 'bg-neutral-primary-soft border-default-medium' }}">
                                        @if ($room_id == $room->id)
                                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                      d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Unit Name & Serial Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium mb-1">Unit Name</label>
                                <flux:input type="text" wire:model="name"
                                            class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800" />
                                <small class="text-gray-500 dark:text-gray-400">
                                    Auto-generated based on category and room (e.g. PC1, PC2...).
                                </small>
                            </div>
                            @if ($quantity == 1)
                                <div class="flex flex-col justify-start">
                                    <flux:input label="Serial Number" type="text" wire:model.defer="serial_number"
                                                placeholder="Enter serial number" />
                                </div>
                            @else
                                <div class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-zinc-800 
                                    border border-gray-200 dark:border-zinc-700 rounded-lg px-3 py-2">
                                    Serial numbers will be auto-generated sequentially.
                                </div>
                            @endif
                        </div>

                        <!-- Quantity & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Quantity</label>
                                <flux:input type="number" wire:model.defer="quantity" min="1"
                                            wire:change="generateUnitName" :disabled="$mode === 'edit'" class="w-full" />
                                <small class="text-gray-500 dark:text-gray-400 block leading-tight">
                                    If quantity is greater than 1, serial numbers will be auto-generated sequentially.
                                </small>
                            </div>
                            <div class="flex flex-col justify-start">
                                <flux:select wire:model.defer="status" label="Status">
                                    <flux:select.option value="">Select Status</flux:select.option>
                                    <flux:select.option value="Operational">Operational</flux:select.option>
                                    <flux:select.option value="Non-operational">Non-operational</flux:select.option>
                                    <flux:select.option value="Needs Repair">Needs Repair</flux:select.option>
                                </flux:select>
                            </div>
                        </div>

                        <!-- Toggle Attach Parts -->
                        <div class="mt-4">
                            <template x-if="!showParts">
                                <button type="button" class="text-blue-600 hover:underline" @click="showParts = true">
                                    + Attach Parts
                                </button>
                            </template>
                            <template x-if="showParts">
                                <button type="button" class="text-red-600 hover:underline" @click="showParts = false">
                                    - Don't Attach Parts
                                </button>
                            </template>
                        </div>

                    </form>
                </div>

                <!-- Right Column: Assign Parts -->
                <div class="border-t md:border-t-0 md:border-l pt-4 md:pt-0 pl-0 md:pl-6" x-show="showParts" x-transition>
                    <livewire:system-units.unit-assign-parts :unitId="$unitId" />
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 sticky bottom-0">
            <button type="button"
                    class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600"
                    wire:click="$dispatch('closeModal')">
                Cancel
            </button>
            <button type="submit" form="unit-form"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled" wire:target="save">
                {{ $mode === 'create' ? 'Add Unit' : 'Update Unit' }}
            </button>
        </div>

    </div>
</div>
