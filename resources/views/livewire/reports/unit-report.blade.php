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

         
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Report Options <span
                        class="text-red-500">*</span></label>

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
                    class="w-full h-[80vh] md:h-[90vh] lg:h-[95vh] min-h-100 border-none"></iframe>
            </div>
        @endif
    </div>

</div>
