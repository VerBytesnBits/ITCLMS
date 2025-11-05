<div
    x-data="{ 
        open: @entangle('show'), 
        selectedAction: @entangle('selectedAction') 
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/40 backdrop-blur-sm"
>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Delete Confirmation
        </h2>

        <p class="text-gray-600 dark:text-gray-300 mb-5">
            Please select an action to perform on this item.
        </p>

        <div class="space-y-3 mb-5">
            <label class="flex items-center gap-3 cursor-pointer">
                <input
                    type="radio"
                    x-model="selectedAction"
                    value="delete"
                    class="text-red-600 focus:ring-red-600"
                >
                <span>Permanently Delete</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input
                    type="radio"
                    x-model="selectedAction"
                    value="junk"
                    class="text-yellow-600 focus:ring-yellow-600"
                >
                <span>Move to Junk</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <button @click="open = false" class="px-4 py-2 border rounded-lg">Cancel</button>

            <button
                @click="$wire.confirmAction()"
                :disabled="!selectedAction"
                class="px-4 py-2 rounded-lg text-white bg-red-600 hover:bg-red-700
                       disabled:opacity-50 disabled:cursor-not-allowed"
            >
                Confirm
            </button>
        </div>
    </div>
</div>
