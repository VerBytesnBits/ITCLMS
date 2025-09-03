<div class="p-4 space-y-4">

    <!-- Filters -->
    <div class="flex items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search logs..." class="border p-2 rounded w-full">
        <select wire:model.live="filterModel" class="border p-2 rounded">
            <option value="">All Models</option>
            <option value="SystemUnit">SystemUnit</option>
            <option value="ComponentParts">ComponentParts</option>
            <option value="Peripheral">Peripheral</option>
            <option value="QrGeneration">QrGeneration</option>
        </select>
    </div>

    <!-- Activity Table -->
    <div class="overflow-x-auto">
        <table class="w-full border rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">User</th>
                    <th class="p-2 border">Action</th>
                    <th class="p-2 border">Subject</th>
                    <th class="p-2 border">Properties</th>
                    <th class="p-2 border">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="p-2 border">{{ $log->causer->name ?? 'System' }}</td>
                        <td class="p-2 border">{{ $log->description }}</td>
                        <td class="p-2 border">{{ class_basename($log->subject_type) }}</td>
                        <td class="p-2 border text-sm">
                            @php $props = $log->properties ?? []; @endphp

                            @if(isset($props['attributes']) && count($props['attributes']) > 0)
                                <div><strong>Attributes:</strong></div>
                                <pre>{{ json_encode($props['attributes'], JSON_PRETTY_PRINT) }}</pre>
                            @endif

                            @if(isset($props['old']) && count($props['old']) > 0)
                                <div><strong>Old:</strong></div>
                                <pre>{{ json_encode($props['old'], JSON_PRETTY_PRINT) }}</pre>
                            @endif

                            @if(empty($props['attributes'] ?? []) && empty($props['old'] ?? []))
                                <div>—</div>
                            @endif
                        </td>
                        <td class="p-2 border">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-2 border text-center">No logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-2">
        {{ $logs->links() }}
    </div>

</div>
