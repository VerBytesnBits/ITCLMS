@props(['unit'])
<div x-data="{ open: false, buttonEl: null }">
    <div class="inline-flex rounded-md shadow-sm">
        <!-- View Button -->
        <button type="button" wire:click="openViewModal({{ $unit->id }})"
            class="px-4 py-2 font-medium rounded-l-md border bg-white text-sm text-black dark:text-white hover:bg-blue-50 dark:hover:bg-gray-700 dark:bg-zinc-800">
            View
        </button>

        <!-- Dropdown Trigger -->
        <button type="button" @click="buttonEl = $el; open = !open"
            class="px-2 py-1 rounded-r-md border bg-white text-sm text-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700 dark:bg-zinc-800 dark:text-gray-300">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!-- Teleport Dropdown -->
    <template x-teleport="body">
        <div>
            <div x-show="open" x-transition.opacity.origin.top.duration.200ms @click.away="open = false" x-cloak
                class="absolute z-50 w-44 rounded-lg shadow-lg bg-white dark:bg-zinc-900 ring-1 ring-black/10 dark:ring-white/20 overflow-hidden"
                :style="'top:' + (buttonEl?.getBoundingClientRect().bottom + window.scrollY) +
                'px; left:' + (buttonEl?.getBoundingClientRect().left + window.scrollX) + 'px;'">
                <div class="flex flex-col divide-y divide-gray-100 dark:divide-zinc-800">
                    <button @click="open = false; $wire.openEditModal({{ $unit->id }})"
                        class="px-4 py-2 text-sm font-medium text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors duration-150">
                        Edit
                    </button>

                    <button @click="open = false; $wire.openReportModal({{ $unit->id }})"
                        class="px-4 py-2 text-sm font-medium text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors duration-150">
                        Report
                    </button>

                    <button @click="open = false; $wire.deleteUnit({{ $unit->id }})"
                        class="px-4 py-2 text-sm font-medium text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors duration-150">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
