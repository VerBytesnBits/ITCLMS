<div class="space-y-6">
    <!-- Header -->
    {{-- <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 flex items-center gap-2">
                <flux:icon.chart-pie class="w-6 h-6 text-indigo-500" />
                Reports
            </h2>
            <p class="text-sm text-zinc-500">Generate and export system reports</p>
        </div>
    </div> --}}
    <livewire:dashboard-heading title="Reports" subtitle="Generate and export system reports" icon="cube"
        gradient-from-color="#3b82f7" gradient-to-color="#1e40af" icon-color="text-blue-600" />
    <!-- Filters -->
    <div
        class="flex flex-wrap items-center gap-3 p-4 bg-zinc-50 dark:bg-zinc-800 
               rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
        
        <!-- Report Type -->
        <flux:select wire:model.live="reportType" class="min-w-[160px]">
            <option value="inventory">Inventory</option>
            <option value="qr">QR Codes</option>
            <option value="activity">Activity Log</option>
        </flux:select>

        <!-- Model Filter -->
        <flux:select wire:model.live="filterModel" class="min-w-[160px]">
            <option value="">All Models</option>
            <option value="SystemUnit">System Unit</option>
            <option value="ComponentParts">Component Parts</option>
            <option value="Peripheral">Peripheral</option>
            <option value="QrGeneration">QR Generation</option>
        </flux:select>

        <!-- Search -->
        <flux:input type="text" wire:model.live.debounce.300ms="search" 
            placeholder="Search..." icon="magnifying-glass" class="flex-1 min-w-[180px]" />

        <!-- Date Range -->
        <flux:input type="date" wire:model="dateFrom" class="min-w-[140px]" />
        <span class="text-zinc-400">to</span>
        <flux:input type="date" wire:model="dateTo" class="min-w-[140px]" />

        <!-- Export Buttons -->
        <div class="ml-auto flex gap-2">
            <flux:button wire:click="exportCsv" variant="primary" color="green" class="flex items-center">
               <span>CSV</span>
            </flux:button>

            @if ($reportType == 'qr')
                <flux:button wire:click="exportQrPdf" variant="outline" color="indigo" class="flex items-center">
                    <flux:icon.qr-code class="w-4 h-4 mr-1" /> PDF
                </flux:button>
            @endif
        </div>
    </div>

    <!-- Report Table -->
    <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-md">
        <table class="w-full text-sm">
            <thead class="bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-200 text-xs uppercase sticky top-0 z-10">
                <tr>
                    @if ($reportType == 'inventory')
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Serial</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Condition</th>
                        <th class="p-3 text-left">Room</th>
                    @elseif($reportType == 'qr')
                        <th class="p-3 text-left">Item Type</th>
                        <th class="p-3 text-left">Serial</th>
                        <th class="p-3 text-left">Generated At</th>
                    @elseif($reportType == 'activity')
                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-left">Action</th>
                        <th class="p-3 text-left">Subject</th>
                        <th class="p-3 text-left">Attributes</th>
                        <th class="p-3 text-left">Old</th>
                        <th class="p-3 text-left">Date</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr class="hover:bg-indigo-50/40 even:bg-zinc-50 dark:even:bg-zinc-800/50 transition-colors">
                        @if ($reportType == 'inventory')
                            <td class="p-3">{{ class_basename($record) }}</td>
                            <td class="p-3 font-medium">{{ $record->name ?? '-' }}</td>
                            <td class="p-3">{{ $record->serial_number ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    {{ $record->status ?? '-' }}
                                </span>
                            </td>
                            <td class="p-3">{{ $record->condition ?? '-' }}</td>
                            <td class="p-3">{{ $record->room->name ?? '-' }}</td>
                        @elseif($reportType == 'qr')
                            <td class="p-3">{{ class_basename($record->item_type) }}</td>
                            <td class="p-3">{{ $record->item->serial_number ?? '-' }}</td>
                            <td class="p-3">{{ $record->created_at->format('M d, Y h:i A') }}</td>
                        @elseif($reportType == 'activity')
                            <td class="p-3">{{ $record->causer->name ?? 'System' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700">
                                    {{ ucfirst($record->description) }}
                                </span>
                            </td>
                            <td class="p-3">{{ class_basename($record->subject_type) }}</td>
                            @php $props = $record->properties ?? []; @endphp
                            <td class="p-3 text-xs">
                                <pre class="whitespace-pre-wrap">{{ json_encode($props['attributes'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td class="p-3 text-xs">
                                <pre class="whitespace-pre-wrap">{{ json_encode($props['old'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td class="p-3 text-zinc-500">{{ $record->created_at->format('M d, Y h:i A') }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-10 text-center text-zinc-500">
                           No records found. Try adjusting filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($reportType == 'activity')
        <div class="mt-3">
            {{ $records->links() }}
        </div>
    @endif
</div>
