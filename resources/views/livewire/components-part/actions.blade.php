<div x-data="{ open: false }" class="relative inline-flex space-x-1">

    {{-- View --}}
    <flux:tooltip hoverable>
        <flux:button wire:click="$dispatch('open-view-modal',[{{ $id }}])" 
            variant="primary" icon="eye"
            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br 
                   focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 
                   shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 
                   font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
        </flux:button>
        <flux:tooltip.content class="max-w-[12rem]">
            <p>View</p>
        </flux:tooltip.content>
    </flux:tooltip>

    {{-- Edit --}}
    <flux:tooltip hoverable>
        <flux:button wire:click="$dispatch('open-edit-modal',[{{ $id }}])" 
            variant="primary" icon="pencil"
            class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br 
                   focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 
                   shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 
                   font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
        </flux:button>
        <flux:tooltip.content class="max-w-[12rem]">
            <p>Modify</p>
        </flux:tooltip.content>
    </flux:tooltip>

    {{-- Delete --}}
    <flux:button wire:click="$dispatch('open-delete-modal', [{{ $id }}, 'ComponentParts'])"
        variant="primary" icon="trash"
        class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br 
               focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 
               shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 
               font-medium rounded-base text-sm px-4 py-2.5 inline-flex items-center gap-1">
    </flux:button>
</div>
