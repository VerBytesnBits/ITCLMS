<div>
    <div>
        <livewire:dashboard-heading title="System Unit Report Generator" />


        <div
            class="grid grid-cols-1 gap-4 p-4 bg-gray-50 dark:bg-zinc-700 rounded-xl border border-gray-200 dark:border-zinc-600 
                    md:grid-cols-3 md:items-end">
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Select Lab. Room</label>
                <flux:select wire:model="selectedRoom" class="w-full">
                    <option value="">All Lab. Rooms</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            {{-- <div x-data="{ open: false }" class="relative">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Component Selection <span
                        class="text-gray-500 dark:text-gray-400">(Optional)</span></label>
                <button @click="open = !open"
                    class="w-full flex justify-between items-center bg-gray-100 dark:bg-zinc-700 px-3 py-2 rounded-lg text-sm
                    text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-zinc-600 transition border border-gray-300 dark:border-zinc-600">
                    Filters ({{ count($selectedComponentParts) + count($selectedPeripheralTypes) }})
                    <svg class="w-5 h-5 ml-2 transform transition-transform duration-300"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute z-50 mt-1 right-0 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 
                           rounded-lg shadow-xl w-64 p-3 max-h-96 overflow-y-auto">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b pb-1">System
                        Components</h4>
                    <div class="space-y-1 mb-3">
                        @foreach ($components as $part)
                            <label
                                class="flex items-center gap-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 p-1 rounded cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedComponentParts"
                                    value="{{ $part }}" class="form-checkbox">
                                <span>{{ $part }}</span>
                            </label>
                        @endforeach
                    </div>

                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 border-b pb-1 mt-3">
                        Peripheral Types</h4>
                    <div class="space-y-1">
                        @foreach ($peripherals as $type)
                            <label
                                class="flex items-center gap-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 p-1 rounded cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedPeripheralTypes"
                                    value="{{ $type }}" class="form-checkbox">
                                <span>{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>

                </div>
            </div> --}}

            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Report Options <span
                        class="text-red-500">(Required)</span></label>

                <div
                    class="flex gap-4 p-2 bg-gray-100 dark:bg-zinc-600 border border-gray-300 dark:border-zinc-500 rounded-lg">
                    <flux:checkbox label="Include Components" wire:model="includeComponents" />
                    <flux:checkbox label="Include Peripherals" wire:model="includePeripherals" />
                </div>
            </div>

            <div class="text-center pt-4">
                <flux:button variant="primary" wire:click="previewReport" icon="document-text">
                    Generate Preview
                </flux:button>
            </div>


        </div>


    </div>

    <div class="mt-4">
        @if ($pdfUrl)
            <div class="border border-gray-200 dark:border-zinc-700 rounded-lg overflow-hidden max-w-6xl mx-auto">
                <iframe src="{{ $pdfUrl }}"
                    class="w-full h-[80vh] md:h-[90vh] lg:h-[95vh] min-h-[400px] border-none"></iframe>
            </div>
        @endif
    </div>

</div>
