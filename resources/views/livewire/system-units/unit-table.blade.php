{{-- resources/views/livewire/system-units/unit-table.blade.php --}}

<div
    class="overflow-x-auto bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm mt-6">
    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase text-sm">
                <th class="px-4 py-3 text-center">
                    <div class="font-bold">UNIT NAME</div>
                </th>
                <th class="px-4 py-3 text-center">
                    <div class="font-bold">STATUS</div>
                </th>
                <th class="px-4 py-3 text-center">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($units as $unit)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">

                    {{-- Unit Name --}}
                    <td class="px-4 py-2 text-center font-medium">
                        {{ $unit->name ?? 'Unnamed Unit' }}
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-2 text-center">
                        <x-status-badge :status="$unit['status']" />
                    </td>

                    <td class="px-4 py-2 text-center">
                        <x-unit-actions :unit="$unit" />
                    </td>
                    

                </tr>
            @endforeach
        </tbody>
    </table>

</div>
