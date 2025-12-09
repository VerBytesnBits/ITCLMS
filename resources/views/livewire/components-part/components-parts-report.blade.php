<div class="p-4">
    <livewire:dashboard-heading title="Components Inventory Report"/>
    <div class="flex justify-between items-center mb-4">

        {{-- ROOM FILTER --}}
        <div>
            <label for="room" class="font-semibold">Filter by Room:</label>
            <select wire:model="roomId" id="room" class="border rounded p-1">
                <option value="">All Rooms</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex space-x-2">

            <flux:button 
                wire:click="exportPDF" 
                variant="primary" 
                class="px-4 py-2 rounded">
                Preview Inventory Report
            </flux:button>

            <flux:button 
                wire:click="previewBarcodes" 
                icon="printer" 
                variant="subtle" 
                class="px-4 py-2 rounded">
                Preview Barcodes
            </flux:button>

        </div>

    </div>

    {{-- PDF PREVIEW --}}
    @if ($showPreview)
        <iframe 
            class="w-full h-[600px]" 
            src="data:application/pdf;base64,{{ $pdfBase64 }}">
        </iframe>
    @endif

</div>
