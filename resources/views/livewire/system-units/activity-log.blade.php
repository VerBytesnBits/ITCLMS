<div class="space-y-4 p-4 border rounded shadow-sm bg-white">
    <h2 class="text-lg font-semibold mb-4">
        History of {{ \App\Models\SystemUnit::find($systemUnitId)?->name ?? 'System Unit' }}
    </h2>

    @if($history->count())
        <ul class="space-y-3">
            @foreach($history as $activity)
                @php
                    $item = $activity->properties['item'] ?? $activity->subject?->name ?? '';
                    $remark = $activity->properties['remark'] ?? '';
                    $action = $activity->properties['action_type'] ?? $activity->description;
                    $user = $activity->causer->name ?? 'System';
                    $date = $activity->created_at->format('Y-m-d H:i');
                @endphp

                <li class="p-3 border rounded bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="font-medium text-indigo-600">{{ $action }}</span>
                            <span class="text-gray-800 ml-1">{{ $item }}</span>
                            @if($remark)
                                <span class="text-gray-600 italic ml-1">"{{ $remark }}"</span>
                            @endif
                        </div>
                        <div class="text-right text-gray-400 text-sm">
                            {{ $date }}
                        </div>
                    </div>
                    <div class="mt-1 text-gray-700">
                        by <span class="font-semibold text-green-700">{{ $user }}</span>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            {{ $history->onEachSide(0)->links('components.pagination.simple') }}
        </div>
    @else
        <p class="text-gray-500">No history for this system unit.</p>
    @endif
</div>
