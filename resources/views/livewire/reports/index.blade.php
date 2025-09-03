<div class="p-6 space-y-4">

    <h2 class="text-xl font-semibold">Reports</h2>

    <div class="flex gap-2 items-center flex-wrap">
        <select wire:model.live="reportType" class="border p-2 rounded">
            <option value="inventory">Inventory</option>
            <option value="qr">QR Codes</option>
            <option value="activity">Activity Log</option>
        </select>

        <select wire:model.live="filterModel" class="border p-2 rounded">
            <option value="">All Models</option>
            <option value="SystemUnit">SystemUnit</option>
            <option value="ComponentParts">ComponentParts</option>
            <option value="Peripheral">Peripheral</option>
            <option value="QrGeneration">QrGeneration</option>
        </select>

        <input type="text" wire:model.debounce.300ms="search" placeholder="Search..." class="border p-2 rounded">

        <input type="date" wire:model="dateFrom" class="border p-2 rounded">
        <input type="date" wire:model="dateTo" class="border p-2 rounded">

        <button wire:click="exportCsv" class="bg-green-600 text-white px-4 py-2 rounded">Export CSV</button>
        @if ($reportType == 'qr')
            <button wire:click="exportQrPdf" class="bg-blue-600 text-white px-4 py-2 rounded">
                Export QR PDF
            </button>
        @endif

    </div>

    <div class="overflow-x-auto mt-4">
        <table class="w-full border rounded text-sm">
            <thead class="bg-gray-100">
                <tr>
                    @if ($reportType == 'inventory')
                        <th class="p-2 border">Type</th>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Serial</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Condition</th>
                        <th class="p-2 border">Room</th>
                    @elseif($reportType == 'qr')
                        <th class="p-2 border">Item Type</th>
                        <th class="p-2 border">Serial</th>
                        <th class="p-2 border">Generated At</th>
                    @elseif($reportType == 'activity')
                        <th class="p-2 border">User</th>
                        <th class="p-2 border">Action</th>
                        <th class="p-2 border">Subject</th>
                        <th class="p-2 border">Attributes</th>
                        <th class="p-2 border">Old</th>
                        <th class="p-2 border">Date</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        @if ($reportType == 'inventory')
                            <td class="p-2 border">{{ class_basename($record) }}</td>
                            <td class="p-2 border">{{ $record->name ?? '-' }}</td>
                            <td class="p-2 border">{{ $record->serial_number ?? '-' }}</td>
                            <td class="p-2 border">{{ $record->status ?? '-' }}</td>
                            <td class="p-2 border">{{ $record->condition ?? '-' }}</td>
                            <td class="p-2 border">{{ $record->room->name ?? '-' }}</td>
                        @elseif($reportType == 'qr')
                            <td class="p-2 border">{{ class_basename($record->item_type) }}</td>
                            <td class="p-2 border">{{ $record->item->serial_number ?? '-' }}</td>
                            <td class="p-2 border">{{ $record->created_at }}</td>
                        @elseif($reportType == 'activity')
                            <td class="p-2 border">{{ $record->causer->name ?? 'System' }}</td>
                            <td class="p-2 border">{{ $record->description }}</td>
                            <td class="p-2 border">{{ class_basename($record->subject_type) }}</td>
                            @php $props = $record->properties ?? []; @endphp
                            <td class="p-2 border">
                                <pre>{{ json_encode($props['attributes'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td class="p-2 border">
                                <pre>{{ json_encode($props['old'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td class="p-2 border">{{ $record->created_at }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-2 border text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($reportType == 'activity')
        <div class="mt-2">
            {{ $records->links() }}
        </div>
    @endif
</div>
