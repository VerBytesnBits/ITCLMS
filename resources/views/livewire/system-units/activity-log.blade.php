<div class="space-y-6 p-6 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm">
    <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold uppercase mb-4 flex items-center gap-2 text-gray-600 dark:text-gray-400 tracking-wider">
            <flux:icon.clock class="w-5 h-5 inline-block mr-1.5" />
            History of {{ \App\Models\SystemUnit::find($systemUnitId)?->name ?? 'System Unit' }}
        </h2>
    </div>

    @if($history->count())
        <ul class="relative border-l-2 border-blue-500/30 pl-5 space-y-5">
            @foreach($history as $activity)
                @php
                    $item = $activity->properties['item'] ?? $activity->subject?->name ?? '';
                    $remark = $activity->properties['remark'] ?? '';
                    $action = $activity->properties['action_type'] ?? $activity->description;
                    $user = $activity->causer->name ?? 'System';
                    $date = $activity->created_at->format('Y-m-d H:i');
                @endphp

                <li class="relative bg-gray-50 dark:bg-zinc-800/50 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                    <!-- Timeline Dot -->
                    <span class="absolute -left-[0.6rem] top-4 w-3 h-3 bg-blue-600 rounded-full border-2 border-white dark:border-zinc-900"></span>
                    
                    <!-- Main Content -->
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="text-sm">
                                <span class="font-semibold text-blue-700 dark:text-blue-400">{{ $action }}</span>
                                <span class="text-gray-800 dark:text-gray-200 ml-1">{{ $item }}</span>
                                @if($remark)
                                    <span class="text-gray-500 dark:text-gray-400 italic ml-1">“{{ $remark }}”</span>
                                @endif
                            </div>
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                by <span class="font-medium text-green-700 dark:text-green-400">{{ $user }}</span>
                            </div>
                        </div>
                        <div class="text-right text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $date }}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            {{ $history->onEachSide(0)->links('components.pagination.simple') }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
            <flux:icon.inbox class="w-10 h-10 text-gray-400 dark:text-gray-600 mb-2" />
            <p class="text-sm">No history for this system unit.</p>
        </div>
    @endif
</div>
