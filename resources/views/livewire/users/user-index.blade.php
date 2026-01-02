<div class="space-y-6">

    <livewire:dashboard-heading title="Users" subtitle="Manage all users" icon="user-group" gradient-from-color="#ebbc49"
        gradient-to-color="#ccf662" icon-color="text-yellow-300" />

    <div class="flex justify-end mb-4 text-end">
        <flux:modal.trigger name="user-modal">
            <flux:button wire:click="$dispatch('open-user-modal', { mode: 'create' })" variant="primary" color="green"
                icon="plus-circle"
                class="cursor-pointer text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
                Add User
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- render table --}}
    <div class="datatable-wrapper">
        <livewire:users-table />
    </div>
    <livewire:users.form-modal />
    <livewire:common.delete-confirmation />

    {{-- Flash message component --}}
    <div x-data="{ show: false, message: '', type: '' }" x-init="window.addEventListener('flash', e => {
        const data = e.detail[0];
        message = data.message;
        type = data.type;
        show = true;
        setTimeout(() => show = false, 5000);
    
    });" x-show="show" x-transition
        class="fixed top-4 right-4 px-4 py-2 rounded shadow-lg text-white z-50"
        :class="{
            'bg-emerald-600': type === 'success',
            'bg-red-600': type === 'error',
        }"
        style="display: none;">

        <span x-text="message"></span>
    </div>
</div>
