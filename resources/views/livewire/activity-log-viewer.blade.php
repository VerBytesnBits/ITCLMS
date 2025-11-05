<div class="space-y-5">
    <!-- ✅ Success Message -->
    @if (session()->has('message'))
        <div class="px-4 py-2 rounded-lg bg-green-100 text-green-800 text-sm font-medium border border-green-200 dark:bg-green-900/40 dark:text-green-200 dark:border-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- 📋 Header / Filters -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-50 flex items-center gap-2">
            <flux:icon name="history" class="w-5 h-5 text-indigo-600" />
            Activity Logs
        </h2>

        <div class="flex gap-2 w-full sm:w-auto">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search logs..." 
                icon="magnifying-glass"
                class="text-sm shadow-sm w-full sm:w-64 
                       text-zinc-800 dark:text-zinc-50 
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
            />

            <select 
                wire:model.live="filterModel"
                class="px-3 py-2 rounded-lg border border-zinc-200 dark:border-zinc-700 
                       bg-white dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-50 
                       shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
                <option value="">All Models</option>
                <option value="SystemUnit">System Unit</option>
                <option value="ComponentParts">Component Parts</option>
                <option value="Peripheral">Peripheral</option>
                <option value="QrGeneration">QR Generation</option>
            </select>
        </div>
    </div>

    <!-- Divider -->
    <hr class="border-zinc-200 dark:border-zinc-700">

    <!-- 🧾 Activity Stream -->
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm divide-y divide-zinc-100 dark:divide-zinc-700">
        @forelse ($logs as $log)
            <div class="flex items-start gap-4 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition rounded-lg">
                <!-- Icon -->
                <div class="flex-shrink-0 mt-1">
                    @php
                        $iconMap = [
                            'created' => ['circle-plus', 'text-green-500'],
                            'updated' => ['pencil', 'text-blue-500'],
                            'deleted' => ['trash-2', 'text-red-500'],
                        ];
                        [$iconName, $iconColor] = $iconMap[$log->description] ?? ['activity', 'text-zinc-400'];
                    @endphp
                    <flux:icon name="{{ $iconName }}" class="w-6 h-6 {{ $iconColor }}" />
                </div>

                <!-- Log Content -->
                <div class="flex-1 space-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-50 leading-tight">
                                {{ $log->causer->name ?? 'System' }}
                                <span class="text-zinc-500 dark:text-zinc-400 font-normal">
                                    {{ $log->description }}
                                </span>
                                <span class="font-semibold text-indigo-600">
                                    {{ class_basename($log->subject_type) }}
                                </span>

                                @if ($log->subject)
                                    <span class="ml-2 text-xs {{ $log->subject?->trashed() ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}">
                                        {{ $log->subject?->trashed() ? '(Deleted)' : '(Active)' }}
                                    </span>
                                @else
                                    <span class="ml-2 text-xs text-zinc-400">(Not found)</span>
                                @endif
                            </p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $log->created_at->diffForHumans() }} • {{ $log->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>

                        @if ($log->subject?->trashed())
                            <button 
                                wire:click="restoreSubject({{ $log->subject->id }}, '{{ addslashes($log->subject_type) }}')" 
                                class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                Restore
                            </button>
                        @endif
                    </div>

                    <!-- Property Changes -->
                    <div class="mt-2 space-y-1 text-xs">
                        @php $props = $log->properties ?? []; @endphp

                        @if (!empty($props['attributes']))
                            <details class="group bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 rounded-lg p-2">
                                <summary class="cursor-pointer text-green-700 dark:text-green-300 font-semibold group-open:mb-1">New Changes</summary>
                                <ul class="list-disc list-inside text-green-700 dark:text-green-300 space-y-0.5">
                                    @foreach ($props['attributes'] as $key => $value)
                                        <li><span class="font-medium">{{ $key }}:</span> {{ $value }}</li>
                                    @endforeach
                                </ul>
                            </details>
                        @endif

                        @if (!empty($props['old']))
                            <details class="group bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800 rounded-lg p-2">
                                <summary class="cursor-pointer text-red-700 dark:text-red-300 font-semibold group-open:mb-1">Previous Values</summary>
                                <ul class="list-disc list-inside text-red-700 dark:text-red-300 space-y-0.5">
                                    @foreach ($props['old'] as $key => $value)
                                        <li><span class="font-medium">{{ $key }}:</span> {{ $value }}</li>
                                    @endforeach
                                </ul>
                            </details>
                        @endif

                        @if (empty($props['attributes'] ?? []) && empty($props['old'] ?? []))
                            <span class="text-zinc-400 dark:text-zinc-500 italic">No detailed changes</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-zinc-500 dark:text-zinc-400">
                No logs found.
            </div>
        @endforelse
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
