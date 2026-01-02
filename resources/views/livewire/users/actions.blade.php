<div class="flex justify-center gap-2">

    {{-- User modal (edit/view) --}}
    <flux:modal.trigger name="user-modal">

        {{-- Edit user --}}
        <flux:button
            wire:click="$dispatch('open-user-modal', {
                mode: 'edit',
                user: {{ $user->id }}
            })"
            icon="pencil"
            variant="primary"
            class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600
                   hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300
                   dark:focus:ring-green-800 shadow-lg shadow-green-500/50
                   dark:shadow-green-800/80 rounded-base px-3 py-2 text-sm cursor-pointer">
        </flux:button>

    </flux:modal.trigger>


    {{-- Delete confirmation --}}
    <flux:modal.trigger name="delete-confirmation-modal">

        <flux:button
            wire:click="$dispatch('confirm-delete', {
                id: {{ $user->id }},
                dispatchAction: 'delete-user',
                modalName: 'delete-confirmation-modal',
                heading: 'Delete User?',
                subheading: 'You are about to delete <strong>{{ $user->name }}</strong>.<br/>This action cannot be undone.',
                confirmButtonText: 'Delete User'
            })"
            icon="trash"
            variant="primary"
            class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600
                   hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300
                   dark:focus:ring-red-800 shadow-lg shadow-red-500/50
                   dark:shadow-red-800/80 rounded-base px-3 py-2 text-sm cursor-pointer">
        </flux:button>

    </flux:modal.trigger>

</div>
