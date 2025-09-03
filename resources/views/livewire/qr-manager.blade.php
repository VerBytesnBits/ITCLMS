<div class="p-6 space-y-6">

    <h2 class="text-xl font-semibold">QR Manager</h2>

    <!-- Type selection -->
    <div>
        <label>Select Type:</label>
        <select wire:model.live="type" class="border p-2 rounded w-full">
            <option value="unit">Unit</option>
            <option value="component">Component</option>
            <option value="peripheral">Peripheral</option>
        </select>
    </div>

    <!-- Item selection -->
    @if ($records)
        <div>
            <label>Select Item:</label>
            <select wire:model.live="itemId" class="border p-2 rounded w-full">
                <option value="">-- choose --</option>
                @foreach ($records as $id => $serial)
                    <option value="{{ $id }}">{{ $serial }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <!-- Generate button -->
    <!-- Generate button -->
    <div class="mt-4 flex gap-2">
        <button wire:click="generateQr" class="bg-blue-600 text-white px-4 py-2 rounded">
            Generate QR
        </button>

        @if ($itemId)
            <button wire:click="downloadQrPdf" class="bg-green-600 text-white px-4 py-2 rounded">
                Download PDF
            </button>
        @endif
    </div>


    <!-- QR Preview -->
    @if ($qr)
        <div class="mt-6 text-center">
            <h3 class="font-semibold mb-2">QR Preview</h3>
            <img src="data:image/png;base64,{{ $qr }}" class="mx-auto border p-2 rounded">
            <p class="mt-2">Serial ID: {{ $records[$itemId] }}</p>
            <a href="{{ url("/tracking/{$type}/{$records[$itemId]}") }}" target="_blank"
                class="text-blue-600 underline">
                Open Tracking Page
            </a>
        </div>
    @endif

    <!-- Active QR Table -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-2">Active QR Records</h3>
        <table class="w-full border rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Type</th>
                    <th class="p-2 border">Serial ID</th>
                    <th class="p-2 border">Generated At</th>
                    <th class="p-2 border">QR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activeQrsWithQr as $item)
                    @php
                        $qrRecord = $item['record'];
                        $qrBase64 = $item['qr'];
                        $typeMap = [
                            'SystemUnit' => 'Unit',
                            'ComponentParts' => 'Component',
                            'Peripheral' => 'Peripheral',
                        ];
                    @endphp
                    <tr>
                        <td class="p-2 border">{{ $typeMap[class_basename($qrRecord->item_type)] ?? 'Unknown' }}</td>
                        <td class="p-2 border">{{ $qrRecord->item->serial_number ?? 'Deleted' }}</td>
                        <td class="p-2 border">{{ $qrRecord->created_at->format('Y-m-d H:i') }}</td>
                        <td class="p-2 border text-center">
                            @if ($qrBase64)
                                <img src="data:image/png;base64,{{ $qrBase64 }}" class="mx-auto" />
                            @else
                                <span class="text-red-500">QR not available</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
