<div
    x-data="{ open: @entangle('show') }"
    x-show="open"
    x-cloak
    x-transition.opacity.duration.200ms
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
>
    <div
        x-transition.scale.duration.200ms
        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl w-full max-w-md text-center"
    >
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
            Confirm Action
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mb-4">
            Are you sure you want to take action on
            <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                {{ $unitName }}
            </span>?
            <br>
            <span class="text-red-500 font-semibold">This action cannot be undone.</span>
        </p>

        <div class="mb-6 text-left">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                What action would you like to do?
            </label>
            <select
                wire:model.live="action"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
            >
                <option value="">-- Select Action --</option>
                <option value="delete">Delete Permanently</option>
                <option value="decommission">Decommission</option>
                <option value="mark_defective">Mark as Defective</option>
            </select>
        </div>

        <div class="flex justify-center gap-3">
            <button
                wire:click="cancel"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md text-sm font-medium"
            >
                Cancel
            </button>

            <button
                wire:click="confirmAction"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium disabled:opacity-50"
                :disabled="!$wire.action"
            >
                Confirm
            </button>
        </div>
    </div>
</div>
