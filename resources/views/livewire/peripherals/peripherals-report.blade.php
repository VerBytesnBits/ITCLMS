<div class="p-4">
    <livewire:dashboard-heading title="Peripheral Inventory Report" />
    <div
        class="flex justify-between items-end p-4 bg-gray-50 dark:bg-zinc-700 rounded-xl border border-gray-200 dark:border-zinc-600 mb-4">

        <!-- Room filter -->
        <div class="flex items-center gap-2">
            <label for="room" class="text-gray-500 font-semibold whitespace-nowrap">Filter by Room:</label>
            <flux:select wire:model="roomId" id="room" class="border rounded p-1">
                <option value="">All Rooms</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </flux:select>
        </div>

        <!-- Action buttons -->
        <div class="flex space-x-2">

            <flux:button wire:click="previewBarcodes" icon="printer" class="px-4 py-2 rounded">
                Preview Barcodes
            </flux:button>
            <flux:button wire:click="exportPDF" variant="primary" class="px-4 py-2 rounded">
                Preview Inventory Report
            </flux:button>

        </div>

    </div>


    {{-- PDF PREVIEW --}}
    @if ($showPreview)
        <iframe class="w-full h-[600px]" src="data:application/pdf;base64,{{ $pdfBase64 }}">
        </iframe>
    @endif

</div>
