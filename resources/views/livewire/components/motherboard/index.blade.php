<div class="p-4 space-y-6">
    <!-- Header -->
    <div class="relative w-full px-4 py-2">
        <flux:heading size="xl" level="1">{{ __('Motherboards') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage all Motherboard records') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Add Button -->
    <div class="flex justify-start">
        <button wire:click="$dispatch('open-motherboard-form')"
            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition shadow">
            Add Motherboard
        </button>
    </div>

    <!-- Motherboard Table -->
    <div
        class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Unit</th>
                    <th class="px-6 py-4">Serial</th>
                    <th class="px-6 py-4">Brand</th>
                    <th class="px-6 py-4">Model</th>
                    {{-- <th class="px-6 py-4">Socket</th>
                    <th class="px-6 py-4">Form Factor</th>
                    <th class="px-6 py-4">Chipset</th> --}}
                    <th class="px-6 py-4">Condition</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Warranty</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $mb)
                    <tr
                        class="border-t border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                        <td class="px-6 py-4 font-medium text-zinc-800 dark:text-white">
                            {{ $mb->id }}
                        </td>
                        <td class="px-6 py-4">{{ $mb->systemUnit->name ?? '' }}</td>
                        <td class="px-6 py-4">{{ $mb->serial_number }}</td>
                        <td class="px-6 py-4">{{ $mb->brand }}</td>
                        <td class="px-6 py-4">{{ $mb->model }}</td>
                        {{-- <td class="px-6 py-4">{{ $mb->socket }}</td>
                        <td class="px-6 py-4">{{ $mb->form_factor }}</td>
                        <td class="px-6 py-4">{{ $mb->chipset }}</td> --}}
                        <td class="px-6 py-4">{{ $mb->condition }}</td>
                        <td class="px-6 py-4">{{ $mb->status }}</td>
                        <td class="px-6 py-4">{{ $mb->warranty }}</td>

                        <td class="px-6 py-4 text-right space-x-3">
                            <button wire:click="edit({{ $mb->id }})"
                                class="text-blue-500 text-sm font-medium hover:underline cursor-pointer">
                                Edit
                            </button>
                            <button wire:click="delete({{ $mb->id }})"
                                class="text-red-500 text-sm font-medium hover:underline cursor-pointer">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Motherboard Form Modal -->
    @livewire('components.motherboard.form')
</div>
