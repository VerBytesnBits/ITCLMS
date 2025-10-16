<div class="space-y-6">

    <livewire:dashboard-heading title="QR Generator" subtitle="Generate QR for fast tracking" icon="qr-code"
        gradient-from-color="#3b82f6" gradient-to-color="#7c3aed" icon-color="text-blue-500" />
    <!-- QR Preview -->
    @if ($qrBatch)
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200 dark:border-zinc-700 p-5">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-3">QR Preview ({{ count($qrBatch) }})</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($qrBatch as $id => $qrCode)
                    <div
                        class="p-3 border border-gray-200 dark:border-zinc-700 rounded-lg text-center bg-white dark:bg-zinc-900">
                        <img src="data:image/png;base64,{{ $qrCode }}" class="mx-auto w-20" />
                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">{{ $records[$id] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Left Column: QR Batch Generator + Preview -->
        <div class="space-y-6">

            <!-- QR Batch Generator Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200 dark:border-zinc-700 flex flex-col overflow-hidden">



                <!-- Header -->
                <div class="p-5 flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <flux:icon.qr-code class="w-5 h-5 text-white" />
                    <h2 class="text-lg font-semibold text-white dark:text-zinc-100">QR Batch Generator</h2>
                </div>

                <!-- Body -->
                <div class="p-5 flex-1 space-y-5">
                    <!-- Step 1: Select Type -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300">Select Type</label>
                        <select wire:model.live="type"
                            class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg p-2 text-sm bg-white dark:bg-zinc-900 focus:ring-2 focus:ring-blue-500">
                            <option value="">-- choose type --</option>
                            <option value="unit">Unit</option>
                            <option value="component">Component</option>
                            <option value="peripheral">Peripheral</option>
                        </select>
                    </div>

                    <!-- Step 2: Multi-select Items -->
                    @if ($records)
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300">Select
                                Items</label>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" wire:click="selectAll"
                                    {{ count($itemIds) === count($records) ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500" />
                                <span class="text-sm text-gray-500">Select All</span>
                            </div>
                            <select wire:model.live="itemIds" multiple
                                class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg p-2 text-sm h-40 bg-white dark:bg-zinc-900 focus:ring-2 focus:ring-blue-500">
                                @foreach ($records as $id => $serial)
                                    <option value="{{ $id }}">{{ $serial }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400">Hold CTRL to select multiple.</p>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div
                    class="p-5 border-t border-zinc-200 dark:border-zinc-700 flex flex-wrap gap-3 justify-end bg-zinc-50 dark:bg-zinc-900/50">
                    <button wire:click="generateQrBatch"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                        Generate QR(s)
                    </button>

                    @if ($itemIds && $qrBatch)
                        <button wire:click="downloadQrBatchPdf"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                            Download PDF
                        </button>
                    @endif
                </div>
            </div>



        </div>

        <!-- Active QR Records Card -->
        <div
            class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200 dark:border-zinc-700 flex flex-col overflow-hidden">

            <div
                class="p-5 flex items-center gap-2 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-green-500 to-emerald-600">
                <flux:icon.qr-code class="w-5 h-5 text-white" />
                <h2 class="text-lg font-semibold text-white dark:text-zinc-100">Active QR Records</h2>
            </div>

            <!-- Card Body -->
            <div class="p-5 flex-1 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="p-2">Type</th>
                            <th class="p-2">Serial ID</th>
                            <th class="p-2">Generated At</th>
                            <th class="p-2">QR</th>
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
                                class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                                <td class="p-2 text-center">
                                    {{ $typeMap[class_basename($qrRecord->item_type)] ?? 'Unknown' }}
                                </td>
                                <td class="p-2 text-center">{{ $qrRecord->item->serial_number ?? 'Deleted' }}</td>
                                <td class="p-2 text-center">{{ $qrRecord->created_at->format('Y-m-d H:i') }}</td>
                                <td class="p-2 text-center">
                                    <div x-data x-intersect.once="$wire.loadQr({{ $qrRecord->id }})">
                                        @if (isset($activeQrsWithQr[$qrRecord->id]))
                                            @if ($activeQrsWithQr[$qrRecord->id])
                                                <img src="data:image/png;base64,{{ $activeQrsWithQr[$qrRecord->id] }}"
                                                    class="mx-auto w-12" />
                                            @else
                                                <span class="text-xs text-red-400 italic">Deleted Item</span>
                                            @endif
                                        @else
                                            <div
                                                class="flex flex-col items-center justify-center gap-1 text-xs text-gray-400">
                                                <flux:icon.loading class="text-green-500" />
                                                Loading...
                                            </div>
                                        @endif
                                    </div>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card Footer -->
            <div class="p-5 border-t border-zinc-200 dark:border-zinc-700  bg-zinc-50 dark:bg-zinc-900/50">
                {{ $activeQrs->links() }}
            </div>
        </div>


    </div>
</div>
