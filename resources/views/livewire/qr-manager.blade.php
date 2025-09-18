<div class="space-y-6">
    <!-- Header -->
    <livewire:dashboard-heading title="QR Generator" subtitle="Generate QR for fast tracking" icon="qr-code"
        gradient-from-color="#3b82f6" gradient-to-color="#7c3aed" icon-color="text-blue-500" />

    <!-- Step 1: Select Type -->
    <div>
        <label class="block text-sm font-medium mb-1">Select Type:</label>
        <select wire:model.live="type" class="w-full border p-2 rounded">
            <option value="">-- choose type --</option>
            <option value="unit">Unit</option>
            <option value="component">Component</option>
            <option value="peripheral">Peripheral</option>
        </select>
    </div>

    <!-- Step 2: Multi-select Items -->
    @if ($records)
        <div>
            <label class="block text-sm font-medium mb-1">Select Items:</label>
            <div class="flex items-center gap-2 mb-2">
                <input type="checkbox" wire:click="selectAll"
                    {{ count($itemIds) === count($records) ? 'checked' : '' }}>
                <span class="text-sm text-gray-600">Select All</span>
            </div>
            <select wire:model.live="itemIds" multiple class="w-full border p-2 rounded h-40">
                @foreach ($records as $id => $serial)
                    <option value="{{ $id }}">{{ $serial }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Hold CTRL to select multiple.</p>
        </div>
    @endif

    <!-- Step 3: Actions -->
    <div class="flex gap-3">
        <button wire:click="generateQrBatch" class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
            {{ empty($itemIds) ? 'disabled' : '' }}>
            Generate QR(s)
        </button>

        @if ($itemIds && $qrBatch)
            <button wire:click="downloadQrBatchPdf" class="bg-green-600 text-white px-4 py-2 rounded">
                Download All as PDF
            </button>
        @endif
    </div>

    <!-- Step 4: Preview -->
    @if ($qrBatch)
        <div class="mt-6">
            <h3 class="font-semibold mb-3">QR Preview ({{ count($qrBatch) }})</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($qrBatch as $id => $qrCode)
                    <div class="p-3 border rounded shadow text-center">
                        <img src="data:image/png;base64,{{ $qrCode }}" class="mx-auto">
                        <p class="mt-2 text-sm">{{ $records[$id] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Step 5: Active QR Records Table -->
    <h3 class="text-lg font-semibold mb-2">Active QR Records</h3>
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg">

        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-200 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="p-2 border">Type</th>
                    <th class="p-2 border">Serial ID</th>
                    <th class="p-2 border">Generated At</th>
                    <th class="p-2 border">QR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activeQrs as $qrRecord)
                    @php
                        $typeMap = [
                            'SystemUnit' => 'Unit',
                            'ComponentParts' => 'Component',
                            'Peripheral' => 'Peripheral',
                        ];
                    @endphp
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50 odd:bg-white even:bg-gray-200 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
                        <td class="p-2 border">{{ $typeMap[class_basename($qrRecord->item_type)] ?? 'Unknown' }}</td>
                        <td class="p-2 border">{{ $qrRecord->item->serial_number ?? 'Deleted' }}</td>
                        <td class="p-2 border">{{ $qrRecord->created_at->format('Y-m-d H:i') }}</td>
                        <td class="p-2 border text-center">
                            <div wire:init="loadQr({{ $qrRecord->id }})">
                                @if (isset($activeQrsWithQr[$qrRecord->id]))
                                    <img src="data:image/png;base64,{{ $activeQrsWithQr[$qrRecord->id] }}"
                                        class="mx-auto w-16" loading="lazy" />
                                @else
                                    <div class="flex flex-col items-center justify-center gap-1">
                                        <flux:icon.loading class="text-green-500" />
                                        <span class="text-gray-400 text-xs">Loading...</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $activeQrs->links() }}
    </div>

</div>
