<flux:dropdown position="bottom" align="end">
    <!-- Bell button -->
    <button 
        class="relative focus:outline-none"
        @click="$wire.markAllAsRead()"  {{--  mark as read when dropdown opens --}}
    >
        <flux:icon name="bell" variant="mini" class=" text-white dark:text-zinc-400 hover:text-zinc-700" />

        @if ($unreadCount > 0)
            <span
                class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] font-semibold 
                       rounded-full w-4 h-4 flex items-center justify-center animate-pulse shadow-md">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <flux:menu class="w-80 sm:w-96 max-w-[95vw] p-0 overflow-hidden rounded-2xl">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                Recent Activities
            </h3>
            <button 
                wire:click="loadActivities" 
                class="text-xs text-gray-500 hover:text-blue-500 transition">
                Refresh
            </button>
        </div>

        <!-- Activity List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($activities as $activity)
                <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <div class="flex-shrink-0 mt-1">
                        @php
                            $icon = match(true) {
                                str_contains(strtolower($activity->description), 'create') => 'plus-circle',
                                str_contains(strtolower($activity->description), 'update') => 'pencil',
                                str_contains(strtolower($activity->description), 'delete') => 'trash-2',
                                default => 'info',
                            };
                        @endphp
                        <flux:icon name="{{ $icon }}" class="w-4 h-4 text-blue-500 dark:text-blue-400" />
                    </div>

                    <div class="flex-1">
                        <div class="text-sm text-gray-800 dark:text-gray-100 font-medium break-words">
                            {{ $activity->description }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ optional($activity->causer)->name ?? 'System' }} • 
                            {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-5 text-sm text-gray-500 dark:text-gray-400 text-center">
                    No recent activities
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-100 dark:border-gray-800 text-center">
            <a href="{{ route('activitylogs') }}" 
               class="inline-block text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                View all activity logs →
            </a>
        </div>
    </flux:menu>
</flux:dropdown>
