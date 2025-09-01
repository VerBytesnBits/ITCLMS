<div class="p-4 space-y-6">
    <!-- Header -->
    <div class="relative w-full px-4 py-2">
        <flux:heading size="xl" level="1">{{ __('Computer Cases') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage all computer cases') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Add Button -->
    <div class="flex justify-start">
        <button wire:click="$dispatch('open-case-form')"
            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition shadow">
            Add Case
        </button>
    </div>

    <!-- Case Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Unit</th>
                    <th class="px-6 py-4">Brand</th>
                    <th class="px-6 py-4">Model</th>
                    <th class="px-6 py-4">Form Factor</th>
                    <th class="px-6 py-4">Color</th>
                    <th class="px-6 py-4">Condition</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Warranty</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $case)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                        <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">{{ $case->id }}</td>
                        <td class="px-6 py-4">{{ $case->systemUnit->name ?? '' }}</td>
                        <td class="px-6 py-4">{{ $case->brand }}</td>
                        <td class="px-6 py-4">{{ $case->model }}</td>
                        <td class="px-6 py-4">{{ $case->form_factor }}</td>
                        <td class="px-6 py-4">{{ $case->color }}</td>
                        <td class="px-6 py-4">{{ $case->condition }}</td>
                        <td class="px-6 py-4">{{ $case->status }}</td>
                        <td class="px-6 py-4">{{ $case->warranty }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <button wire:click="edit({{ $case->id }})"
                                class="text-blue-500 text-sm font-medium hover:underline cursor-pointer">Edit</button>
                            <button wire:click="delete({{ $case->id }})"
                                class="text-red-500 text-sm font-medium hover:underline cursor-pointer">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Case Form Modal -->
    @livewire('components.computer-case.form')
</div>
