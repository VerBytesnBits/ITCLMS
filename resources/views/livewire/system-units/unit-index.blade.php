<!-- resources/views/livewire/system-units/unit-index.blade.php -->
<div class="w-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">System Units</h1>
        <button wire:click="$dispatch('open-unit-form')"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
            + Add Unit
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow rounded-xl">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr
                    class="bg-gray-50 dark:bg-zinc-800 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Name</th>
                    {{-- <th class="px-6 py-3">Serial</th> --}}
                    <th class="px-6 py-3">Room</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach ($units as $unit)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $unit->id }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                            {{ $unit->name }}
                        </td>
                        {{-- <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            {{ $unit->serial_number ?? '—' }}
                        </td> --}}
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                {{ $unit->room->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'Active' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                    'Inactive' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                    'Maintenance' =>
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                    'Faulty' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$unit->status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' }}">
                                {{ $unit->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="openEditModal({{ $unit->id }})"
                                class="text-blue-500 text-sm font-medium hover:underline cursor-pointer">
                                Edit
                            </button>

                            <button wire:click="deleteUser({{ $unit->id }})"
                                class="text-red-500 text-sm font-medium hover:underline cursor-pointer">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @livewire('system-units.unit-form')
</div>
