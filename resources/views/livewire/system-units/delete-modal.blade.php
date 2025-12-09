<div
    x-data="{ open: @entangle('show') }"
    x-show="open"
    x-cloak
    x-transition.opacity.duration.200ms
    class="fixed inset-0 w-full h-full z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm overflow-y-auto"
>

    <div
        x-transition.scale.duration.200ms
        class="bg-white dark:bg-zinc-900 w-full max-w-md mx-4 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <flux:icon name="triangle-alert" class="w-5 h-5 text-amber-500" />
                <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                    Confirm Action
                </h2>
            </div>
            <button
                wire:click="cancel"
                class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 transition"
            >
                <flux:icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4 text-center">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                You are about to take an action on
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                    {{ $unitName }}
                </span>.
                <br>
                <span class="text-red-500 font-semibold">This action cannot be undone.</span>
            </p>

            <div class="text-left">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Choose an action:
                </label>
                <div class="relative">
                    <select
                        wire:model.live="action"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 py-2 pl-3 pr-8 appearance-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                        <option value="">Select Action</option>
                        <option value="delete">Delete Permanently</option>
                        <option value="decommission">Decommission</option>

                    </select>
                    <flux:icon name="chevron-down" class="w-4 h-4 absolute right-3 top-2.5 text-zinc-400 pointer-events-none" />
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 bg-zinc-50 dark:bg-zinc-800 border-t border-zinc-100 dark:border-zinc-700">
            <button
                wire:click="cancel"
                class="px-4 py-2 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition"
            >
                Cancel
            </button>

            <button
                wire:click="confirmAction"
                :disabled="!$wire.action"
                class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-60 transition"
            >
                Confirm
            </button>
        </div>
    </div>
</div>
