<div class="p-4 max-w-full">

    <livewire:dashboard-heading title="Components Inventory Report" />

    <!-- Filter + Actions -->
    <div
        class="flex flex-wrap items-end gap-4 p-4 bg-gray-50 dark:bg-zinc-700 rounded-xl border border-gray-200 dark:border-zinc-600">

        <!-- Room filter -->
        <div class="flex flex-1 min-w-[200px] items-center gap-2">
            <label for="room" class="text-gray-500 font-semibold whitespace-nowrap">Filter by Room:</label>
            <flux:select wire:model="roomId" id="room" class="border rounded p-1 flex-1">
                <option value="">All Rooms</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </flux:select>
        </div>

        <!-- Action buttons -->
        <div class="flex flex-wrap gap-2 justify-end flex-1 min-w-[200px]">
            <flux:button wire:click="previewBarcodes" icon="printer" class="px-4 py-2 rounded flex-1 min-w-[150px]">
                Preview Barcodes
            </flux:button>
            <flux:button wire:click="exportPDF" variant="primary" class="px-4 py-2 rounded flex-1 min-w-[150px]">
                Preview Inventory Report
            </flux:button>
        </div>

    </div>

    {{-- PDF PREVIEW --}}
    @if ($showPreview)
        <iframe class="w-full h-[600px] mt-4" src="data:application/pdf;base64,{{ $pdfBase64 }}">
        </iframe>
    @endif

</div>
