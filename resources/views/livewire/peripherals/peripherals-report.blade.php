<div class="p-4">
    <livewire:dashboard-heading title="Peripheral Inventory Report"/>
    <div class="flex justify-between items-center mb-4">
        <div>
            <label for="room" class="font-semibold">Filter by Room:</label>
            <select wire:model="roomId" id="room" class="border rounded p-1">
                <option value="">All Rooms</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex space-x-2">
            <flux:button wire:click="exportPDF" variant="primary" class="px-4 py-2 rounded">
                Preview Inventory Report
            </flux:button>

            <flux:button wire:click="previewBarcodes" icon="printer" variant="subtle" class="px-4 py-2 rounded">
                Preview Barcodes
            </flux:button>

            {{-- 
    <button wire:click="downloadPDF" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
        Download PDF
    </button> --}}
        </div>

    </div>

    @if ($showPreview)
        <iframe class="w-full h-[600px]" src="data:application/pdf;base64,{{ $pdfBase64 }}"></iframe>
    @endif

    {{-- <div class="mt-4">
        @forelse ($grouped as $roomName => $items)
            <div class="mb-6">
                <h2 class="text-lg font-bold border-b pb-1">{{ $roomName }}</h2>
                <ul class="list-disc pl-6 mt-2 text-sm">
                    @foreach ($items as $item)
                        <li>{{ $item->name }} — {{ $item->brand ?? 'N/A' }} ({{ $item->status ?? 'N/A' }})</li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="text-gray-500">No peripherals found.</p>
        @endforelse
    </div> --}}

</div>
