<div class="space-y-4">
    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-2">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            placeholder="Search logs..."
            class="w-full sm:flex-[2] px-4 py-2 rounded-xl border border-zinc-200 bg-zinc-50 
                   text-sm shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        />

        <select 
            wire:model.live="filterModel"
            class="w-full sm:flex-1 px-4 py-2 rounded-xl border border-zinc-200 bg-zinc-50 
                   text-sm shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
        >
            <option value="">All Models</option>
            <option value="SystemUnit">SystemUnit</option>
            <option value="ComponentParts">ComponentParts</option>
            <option value="Peripheral">Peripheral</option>
            <option value="QrGeneration">QrGeneration</option>
        </select>
    </div>

    <!-- Activity Table -->
    <div class="overflow-x-auto rounded-xl border border-zinc-200 shadow-sm bg-white">
        <table class="w-full text-sm">
            <thead class="bg-zinc-200 text-zinc-700 text-xs uppercase">
                <tr>
                    <th class="p-3 text-left">User</th>
                    <th class="p-3 text-left">Action</th>
                    <th class="p-3 text-left">Target</th>
                    <th class="p-3 text-left">Changes</th>
                    <th class="p-3 text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr class="hover:bg-zinc-50 even:bg-zinc-200 transition-colors">
                        <!-- User -->
                        <td class="p-3 font-medium text-zinc-800">
                            {{ $log->causer->name ?? 'System' }}
                        </td>

                        <!-- Description -->
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700 font-medium">
                                {{ ucfirst($log->description) }}
                            </span>
                        </td>

                        <!-- Subject type -->
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-zinc-200 text-zinc-700">
                                {{ class_basename($log->subject_type) }}
                            </span>
                        </td>

                        <!-- Changes -->
                        <td class="p-3 text-xs space-y-1">
                            @php $props = $log->properties ?? []; @endphp

                            @if (!empty($props['attributes']))
                                <div>
                                    <span class="text-green-700 font-semibold">New:</span>
                                    <ul class="list-disc list-inside text-green-600">
                                        @foreach ($props['attributes'] as $key => $value)
                                            <li><span class="font-medium">{{ $key }}:</span> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (!empty($props['old']))
                                <div>
                                    <span class="text-red-700 font-semibold">Old:</span>
                                    <ul class="list-disc list-inside text-red-600">
                                        @foreach ($props['old'] as $key => $value)
                                            <li><span class="font-medium">{{ $key }}:</span> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (empty($props['attributes'] ?? []) && empty($props['old'] ?? []))
                                <span class="text-gray-400 italic">No changes</span>
                            @endif
                        </td>

                        <!-- Timestamp -->
                        <td class="p-3 text-zinc-500 text-sm">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-zinc-500">
                            No logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
